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
    actions: {
        /***
         * Clear the store. Basically, it reset cardsByCategory and categories.
         * @returns {void}
         */
        clearStore(): void {
            this.cardsByCategory = {};
            this.categories = [];
        },

        /***
         * A full generic function to make API requests. It handle the request and return only datas.
         * EXCEPTS if you set returnCompleteResponse to true. In this case, you have to handle errors by yourself.
         * 
         * @param method: "get" | "post"
         * @param url: string
         * @param contentType: string
         * @param returnCompleteResponse: boolean
         * @param requestData: any
         * @returns {Promise<T>}
         */
        async apiRequest<T>(
            method: string,
            url: string,
            contentType: string,
            returnCompleteResponse: boolean = false,
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

        /***
         * Fetch cards from a category. If the cards are already in the store, it will return them.
         * Else, it will fetch them from the API and store them.
         * @param category_id: number
         * @returns {Promise<Card[]>}
         */
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

        /***
         * Fetch categories. If the categories are already in the store, it will return them.
         * Else, it will fetch them from the API and store them.
         * @returns {Promise<Category[]>}
         */
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

        /***
         * Fetch reward status
         * @returns {Promise<RewardStatus>}
         */
        async fetchRewardStatus(): Promise<RewardStatus> {
            return await this.apiRequest<RewardStatus>("get", "reward/status", this.contentType);
        },
        
        /***
         * Open daily reward and get cards
         * @returns {Promise<Card[]>}
         */
        async fetchReward(): Promise<Card[]> {
            return await this.apiRequest<Card[]>("post", "reward/open", this.contentType);
        },

        /***
         * Fetch entities from a card
         * @param card_id: number
         * @returns {Promise<Entity[]>}
         */
        async fetchEntitiesFromCard(card_id: number): Promise<Entity[]> {
            return await this.apiRequest<Entity[]>("get", `entities/${card_id}`, this.contentType);
        },

        /***
         * Enable a sharing code for a card and return it
         * @param entity_id: number
         * @returns {Promise<Code>}
         */
        async fetchSharingCode(entity_id: number): Promise<Code> {
            return await this.apiRequest<Code>("post", `card/share/enable/${entity_id}`, this.contentType);
        },

        /***
         * Disable a sharing code for a card
         * @param entity_id: number
         * @returns {Promise<void>}
         */
        async disableSharingCode(entity_id: number): Promise<void> {
            await this.apiRequest<void>("post", `card/share/disable/${entity_id}`, this.contentType);
        },

        /***
         * Fetch a card from a sharing code.
         * @param code: string
         * @returns {Promise<IResponse>}
         */
        async fetchCardFromCode(code: string): Promise<IResponse> {
            return await this.apiRequest<IResponse>("post", `card/share/activate/${code}`, this.contentType, true);
        }
    }
});