import {defineStore} from "pinia";
import { useLocalStorage } from "@vueuse/core"
import {request, SRV_STATUS} from "./requests.ts";
import router from "../router";

interface IResponse {
    success: boolean,
    status: number,
    error: string,
    message: string,
    content: any
}

const basicState = {username: '', email: '', is_verified: false, is_connected: false};

export const useUserStore = defineStore("user-store", {
    state: () => ({
        authUser: useLocalStorage("authUser", basicState),
        token: useLocalStorage("token", ""),
        contentType: 'application/json',
        data: useLocalStorage("data", <IResponse | null>{}),
    }),
    getters: {
        getAuthUser: (state) => state.authUser,
        getToken: (state) => state.token,
    },
    actions: {
        async login(logData:any): Promise<void> {
            this.reset();
            try {
                this.data = await request("post", "login", "", this.contentType, logData);
                if(this.data?.status === SRV_STATUS.SUCCESS && this.data.content.verified) {
                    this.token = this.data.content.token;
                    this.authUser.is_connected = true;
                    await this.fetchUser();
                }
            } catch (error) {
                this.data.status = SRV_STATUS.INTERNAL_ERROR;
                this.data.content.message = "Erreur interne au serveur, veuillez contacter l'administrateur";
                console.error("Error in login function:", error);
            }
        },
        async logout() {
            if(await this.isValidToken() === false) {
                await router.push({name: "Connexion"});
            } else {
                try {
                    this.data = await request("post", "logout", this.token, this.contentType, '');
                    if (this.data?.status === SRV_STATUS.SUCCESS) {
                        this.reset();
                    }
                } catch (error) {
                    console.error("Error in logout function:", error);
                }
            }
        },
        async register(registerData: any):Promise<void> {
            this.reset();
            try {
                this.data = await request("post", "register", "", this.contentType, registerData);
                if(this.data?.status === SRV_STATUS.SUCCESS) {
                    console.log(this.data);
                    this.token = this.data?.content.token;
                    await this.fetchUser();
                }
            } catch (error) {
                console.error("Error in register function:", error);
            }
        },
        async fetchUser() {
            /*if(await this.isValidToken() === false) {
                await router.push({name: "Connexion"});
            } else {*/
                try {
                    if (this.token) {
                        this.data = await request("get", "account/get", this.token, this.contentType, '');
                        this.authUser = this.data?.content;
                    }
                } catch (error) {
                    console.log("Error in fetchUser function : " + error);
                }
            //}
        },
        async activateAccount(code: string): Promise<void> {
            if(await this.isValidToken() === false) {
                await router.push({name: "Connexion"});
            } else {
                try {
                    this.data = await request("post", "account/activate/verify/" + code, "", "", "");
                    if (this.data?.status === SRV_STATUS.SUCCESS) {
                        await this.fetchUser();
                    }
                } catch (error) {
                    console.log("activeAccount - errors" + error);
                }
            }
        },
        async resendActivateAccount(): Promise<void> {
            if(await this.isValidToken() === false) {
                await router.push({name: "Connexion"});
            } else {
                try {
                    this.data = await request("post", "account/activate/resend", this.token, this.contentType, "");
                } catch (error) {
                    console.log("resendActivateAccount - errors" + error);
                }
            }
        },
        async updateUserAccount(data:any, url:string) {
            if(await this.isValidToken() === false) {
                await router.push({name: "Connexion"});
            } else {
                try {
                    this.data = await request("post", url, this.token, this.contentType, data);
                    if(this.data?.status === SRV_STATUS.SUCCESS) {
                        await this.fetchUser();
                    }
                } catch (errors) {
                    console.log("Error in updateUserAccount" + errors);
                }
            }
        },
        async isValidToken() {
            try {
                this.data = await request("get", "token/check", this.token, "", "");
                if(this.data?.status === SRV_STATUS.SUCCESS) {
                    this.authUser.is_connected = this.data?.content.valid === true;
                    return this.data?.content.valid;
                }
            } catch (errors) {
                console.log("Errors in isValidToken : " + errors);
            }
        },
        reset(): void {
            this.authUser = basicState;
            this.token = '';
            this.data = null;
        }
    }
});