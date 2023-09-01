import { defineStore } from "pinia";
import { request, SRV_STATUS, IResponse } from "./requests.ts";
import { useUserStore } from "./useUserStore.ts"
import { Card } from "../models/Card.vue";
import { Category } from "../models/Category.vue";
import { Entity } from "../models/Entity.vue";
import { Code } from "../models/Code.vue";
import { RewardStatus } from "../models/RewardStatus.vue";

export const useCardStore = defineStore("card-store", {
    state: () => ({
        contentType: 'application/json',
        cardsByCategory: <{ [key: number]: Card[] }>{},
        categories: <Category[]>[],
    }),
    getters: {
    },
    actions: {
        clearStore(): void {
            this.cardsByCategory = {};
            this.categories = [];
        },

        /* The `apiRequest` function is a generic function used to make API requests. It takes in
        several parameters: */
        async apiRequest<T>(
            method: string,
            url: string,
            contentType: string,
            returnCompleteResponse: boolean = false, // if turn to true, you HAVE TO handle error by yourself
            requestData: any = null
        ): Promise<T> {
            const userStore = useUserStore();

            try {
                const response = await request(method, url, userStore.token, contentType, requestData);
                if (returnCompleteResponse) {
                    return response as IResponse as T;
                }
                else {
                    if (response.status === SRV_STATUS.SUCCESS && response.success) {
                        return response.content as T;
                    } else {
                        throw new Error(`API error: ${url}: ${response.error}`);
                    }
                }
            } catch (error) {
                throw new Error(`Request error: ${url}: ${error}`);
            }
        },

        async fetchCardsFromCategory(category_id: number): Promise<Card[]> {
            if (this.cardsByCategory[category_id] !== undefined) {
                console.log("fetching from store");
                return this.cardsByCategory[category_id];
            }
            else {
                console.log("fetching from api");
                let response = {
                    cards: <Card[]>[]
                };
                response = await this.apiRequest<any>("get", `collection/filter/category/${category_id}`, this.contentType);
                this.cardsByCategory[category_id] = response.cards;
                return this.cardsByCategory[category_id];
            }
        },
        async fetchCategories(): Promise<Category[]> {
            if (this.categories.length > 0) {
                console.log("fetching categories from store");
                return this.categories;
            }
            else {
                console.log("fetching from api");
                this.categories = await this.apiRequest<Category[]>("get", `categories`, this.contentType);
                return this.categories;
            }
        },

        async fetchRewardStatus(): Promise<RewardStatus> {
            return await this.apiRequest<RewardStatus>("get", "reward/status", this.contentType);
        },
        async fetchReward(): Promise<Card[]> {
            return await this.apiRequest<Card[]>("post", "reward/open", this.contentType);
        },

        async fetchEntitiesFromCard(card_id: number): Promise<Entity[]> {
            return await this.apiRequest<Entity[]>("get", `entities/${card_id}`, this.contentType);
        },

        async fetchSharingCode(entity_id: number): Promise<Code> {
            return await this.apiRequest<Code>("post", `card/share/enable/${entity_id}`, this.contentType);
        },

        async disableSharingCode(entity_id: number): Promise<void> {
            await this.apiRequest<void>("post", `card/share/disable/${entity_id}`, this.contentType);
        },

        async fetchCardFromCode(code: string): Promise<IResponse> {
            return await this.apiRequest<IResponse>("post", `card/share/activate/${code}`, this.contentType, true);
        }
    }
});