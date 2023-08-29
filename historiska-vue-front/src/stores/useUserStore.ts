import {defineStore} from "pinia";

import {request} from "./requests.ts";

interface IAuthUser {
    authUser: {
        username: string,
        email: string,
        is_verified: boolean
    },
    token: string,
}
export const useUserStore = defineStore("user-store", {
    state: () : IAuthUser => <IAuthUser>({
        authUser: null as null | IAuthUser["authUser"],
        token: '',
    }),
    getters: {
        getAuthUser: (state) => state.authUser,
        getToken: (state) => state.token,
    },
    actions: {
        async login(logInfo:any): Promise<void> {
            try {
                const data = await request("post", "login", "", "application/json", logInfo);
                if(data.status === 200 && data.content.verified) {
                    this.token = data.content.token;
                    await this.getUser();
                }
            } catch (error) {
                // TODO Handle errors here
                console.error("Error in login:", error);
            }
        },
        async getUser() {
            try {
                if(this.token) {
                    const data = await request("get", "account/get", this.token, "application/json", '');
                    this.authUser = data.content;
                } else {
                    // TODO Rediriger vers la page de connexion
                }

            } catch(error) {
                // TODO Handle error here
                console.log("FONCTIONNE PAS");
            }
        }
    }
});