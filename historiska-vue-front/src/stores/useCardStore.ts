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
        refreshCollection() {
            this.collection = null;
            this.fetchCollection();
        },
        async fetchCollection(): Promise<IResponse | null> {
            const userStore = useUserStore();
            try {
                this.collection = await request("get", "collection", userStore.token, this.contentType, "");
                if (this.collection?.status === SRV_STATUS.SUCCESS && this.collection.content.verified) {
                }
                return this.collection;
            } catch (error) {
                // TODO Handle errors here
                console.error("Error in card function:", error);
                throw Error("Error in card function");
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
                throw Error(`Error from fetchCardsFromCategory: ${ error }`);
            }
        },
    }
});