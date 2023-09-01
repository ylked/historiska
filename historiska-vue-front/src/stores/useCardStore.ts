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
        async fetchCardsFromCategory(category_id: number): Promise<Card[]> {
            if (this.cardsByCategory[category_id] !== undefined) {
                console.log("fetching from store");
                return this.cardsByCategory[category_id];
            }
            else {
                const userStore = useUserStore();
                console.log("fetching from api");
                await request("get", `collection/filter/category/${category_id}`, userStore.token, this.contentType, "")
                    .then((response) => {
                        if (response.status === SRV_STATUS.SUCCESS && response.success) {
                            this.cardsByCategory[category_id] = response.content.cards;
                        }
                        else {
                            throw Error(`API error: fetchCardsFromCategory: ${response.error}`);
                        }
                    })
                    .catch((error) => {
                        throw Error(`Request error: fetchCardsFromCategory: ${error}`);
                    });
                return this.cardsByCategory[category_id];
            }
        },
        async fetchCategories(): Promise<Category[]> {
            if (this.categories.length > 0) {
                console.log("fetching categories from store");
                return this.categories;
            }
            else {
                const userStore = useUserStore();
                console.log("fetching categories from api");
                await request("get", `categories`, userStore.token, this.contentType, "")
                    .then((response) => {
                        if (response.status === SRV_STATUS.SUCCESS && response.success) {
                            this.categories = response.content;
                        }
                        else {
                            throw Error(`API error: fetchCategories: ${response.error}`);
                        }
                    })
                    .catch((error) => {
                        throw Error(`Request error: fetchCategories: ${error}`);
                    });
                return this.categories;
            }
        },
        async fetchRewardStatus(): Promise<RewardStatus> {
            const userStore = useUserStore();
            let rewardStatus: RewardStatus = {
                is_available: false,
                next_opening: ""
            };

            await request("get", "reward/status", userStore.token, this.contentType, "")
                .then((response) => {
                    if (response.status === SRV_STATUS.SUCCESS && response.success) {
                        rewardStatus = response.content;
                    }
                    else {
                        throw Error(`API error: fetchRewardStatus: ${response.error}`);
                    }
                })
                .catch((error) => {
                    throw Error(`Request error: fetchRewardStatus: ${error}`);
                });
            return rewardStatus;
        },

        async fetchReward(): Promise<Card[]> {
            const userStore = useUserStore();
            let cards: Card[] = [];

            await request("post", "reward/open", userStore.token, this.contentType, "")
                .then((response) => {
                    if (response.status === SRV_STATUS.SUCCESS && response.success) {
                        cards = response.content;
                    }
                    else {
                        throw Error(`API error: fetchReward: ${response.error}`);
                    }
                })
                .catch((error) => {
                    throw Error(`Request error: fetchReward: ${error}`);
                });
            return cards;
        },
        async fetchEntitesFromCard(card_id: number): Promise<Entity[]> {
            const userStore = useUserStore();
            let entites: Entity[] = [];
            await request("get", `entities/${card_id}`, userStore.token, this.contentType, "")
                .then((response) => {
                    if (response.status === SRV_STATUS.SUCCESS && response.success) {
                        entites = response.content;
                    }
                    else {
                        throw Error(`API error: fetchEntitesFromCard: ${response.error}`);
                    }
                })
                .catch((error) => {
                    throw Error(`Request error: fetchEntitesFromCard: ${error}`);
                });
            return entites;
        },
        async fetchSharingCode(entity_id: number): Promise<Code> {
            const userStore = useUserStore();
            let code: Code = { code: "" };
            await request("post", `card/share/enable/${entity_id}`, userStore.token, this.contentType, "")
                .then((response) => {
                    if (response.status === SRV_STATUS.SUCCESS && response.success) {
                        code = response.content;
                    }
                    else {
                        throw Error(`API error: fetchSharingCode: ${response.error}`);
                    }
                })
                .catch((error) => {
                    throw Error(`Request error: fetchSharingCode: ${error}`);
                });
            return code;
        },
        async disableSharingCode(entity_id: number): Promise<void> {
            const userStore = useUserStore();
            await request("post", `card/share/disable/${entity_id}`, userStore.token, this.contentType, "")
                .then((response) => {
                    if (response.status !== SRV_STATUS.SUCCESS || !response.success) {
                        throw Error(`API error: disableSharingCode: ${response.error}`);
                    }
                })
                .catch((error) => {
                    throw Error(`Request error: disableSharingCode: ${error}`);
                });
        },
        async fetchCardFromCode(code: string): Promise<IResponse> {
            const userStore = useUserStore();
            try {
                const card = await request("post", `card/share/activate/${code}`, userStore.token, this.contentType, "");
                return card;
            } catch (error) {
                throw Error(`from fetchCardFromCode: ${error}`);
            }
        }
    }
});