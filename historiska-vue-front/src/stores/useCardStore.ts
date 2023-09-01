import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core"
import { request, SRV_STATUS, IResponse } from "./requests.ts";
import { useUserStore } from "./useUserStore.ts"

export const useCardStore = defineStore("card-store", {
    state: () => ({
        contentType: 'application/json',
        collection: useLocalStorage("collection", <IResponse | null>{})
    }),
    getters: {
        
    },
    actions: {
        async fetchCollection(): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                this.collection = await request("get", "collection", userStore.token, this.contentType, "");
                if (this.collection?.status === SRV_STATUS.SUCCESS && this.collection.content.verified) {
                }
                return this.collection;
            } catch (error) {
                throw Error(`Error from fetchCollection: ${ error }`);
            }
        },
        async fetchCardsFromCategory(category_id: number): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                const cards = await request("get", `collection/filter/category/${category_id}`, userStore.token, this.contentType, "");
                if (cards?.status === SRV_STATUS.SUCCESS && cards.content.verified) {
                    // ouai ouai tkt
                }
                return cards;
            } catch (error) {
                throw Error(`Error from fetchCardsFromCategory: ${ error }`);
            }
        },
        async fetchCategories(): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                const categories = await request("get", "categories", userStore.token, this.contentType, "");
                if (categories?.status === SRV_STATUS.SUCCESS && categories.content.verified) {
                    // ouai ouai tkt
                }
                return categories;
            } catch (error) {
                throw Error(`Error from fetchCategories: ${ error }`);
            }
        },
        async fetchRewardStatus(): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                const rewardStatus = await request("get", "reward/status", userStore.token, this.contentType, "");
                if (rewardStatus?.status === SRV_STATUS.SUCCESS && rewardStatus.content.verified) {
                    // ouai ouai tkt
                }
                return rewardStatus;
            } catch (error) {
                throw Error(`Error from fetchRewardStatus: ${ error }`);
            }
        },
        async fetchReward(): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                const reward = await request("post", "reward/open", userStore.token, this.contentType, "");
                if (reward?.status === SRV_STATUS.SUCCESS && reward.content.verified) {
                    // ouai ouai tkt
                }
                return reward;
            } catch (error) {
                throw Error(`Error from fetchReward: ${ error }`);
            }
        },
        async fetchEntitesFromCard(card_id: number): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                const entities = await request("get", `entities/${card_id}`, userStore.token, this.contentType, "");
                if (entities?.status === SRV_STATUS.SUCCESS && entities.content.verified) {
                    // ouai ouai tkt
                }
                return entities;
            } catch (error) {
                throw Error(`Error from fetchReward: ${ error }`);
            }
        },
        async fetchSharingCode(entity_id: number): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                const sharingCode = await request("post", `card/share/enable/${entity_id}`, userStore.token, this.contentType, "");
                if (sharingCode?.status === SRV_STATUS.SUCCESS && sharingCode.content.verified) {
                    // ouai ouai tkt
                }
                return sharingCode;
            } catch (error) {
                throw Error(`Error from fetchSharingCode: ${ error }`);
            }
        },
        async disableSharingCode(entity_id: number): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                const sharingCode = await request("post", `card/share/disable/${entity_id}`, userStore.token, this.contentType, "");
                if (sharingCode?.status === SRV_STATUS.SUCCESS && sharingCode.content.verified) {
                    // ouai ouai tkt
                }
                return sharingCode;
            } catch (error) {
                throw Error(`Error from disableSharingCode: ${ error }`);
            }
        },
        async fetchCardFromCode(code: string): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                const card = await request("post", `card/share/activate/${code}`, userStore.token, this.contentType, "");
                if (card?.status === SRV_STATUS.SUCCESS && card.content.verified) {
                    // ouai ouai tkt
                }
                return card;
            } catch (error) {
                throw Error(`from fetchCardFromCode: ${ error }`);
            }
        }
    }
});