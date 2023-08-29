import {defineStore} from "pinia";

import {request} from "./requests.ts";

interface IAuthUser {
    authUser: {
        username: string,
        email: string,
        is_verified: boolean
    },
    token: string,
    contentType: string
}

const STATUS_CODE: number = 200;
export const useUserStore = defineStore("user-store", {
    state: () : IAuthUser => <IAuthUser>({
        authUser: null as null | IAuthUser["authUser"],
        token: '',
        contentType: 'application/json'
    }),
    getters: {
        getAuthUser: (state) => state.authUser,
        getToken: (state) => state.token,
    },
    actions: {
        async login(logData:any): Promise<void> {
            try {
                const data = await request("post", "login", "", this.contentType, logData);
                if(data.status === STATUS_CODE && data.content.verified) {
                    this.token = data.content.token;
                    await this.getUser();
                }
            } catch (error) {
                // TODO Handle errors here
                console.error("Error in login function:", error);
            }
        },
        async logout() {
            try {
                const data = await request("post", "logout", this.token, this.contentType, '');
                if(data.status === STATUS_CODE) {
                    // TODO
                    console.log("LOG OUT");
                }
            } catch (error) {
                // TODO Handle errors here
            }
        },
        async register(registerData):Promise<void> {
            try {
                // TODO
            } catch (error) {
                // TODO Handle errors
                console.error("Error in register function:", error);
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
        },
        async updateUserAccount(data) {
            // TODO route api : /account/update/email => mail
            // /account/update/username => username
            // /account/update/password => password
        },
    }
});