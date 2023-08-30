import {defineStore} from "pinia";
import { useLocalStorage } from "@vueuse/core"
import {request, SRV_STATUS} from "./requests.ts";

interface IResponse {
    success: boolean,
    status: number,
    error: string,
    message: string,
    content: any
}

const basicState = {username: '', email: '', is_verified: false};

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
        getAccountActivate:(state) => state.authUser.is_verified
    },
    actions: {
        async login(logData:any): Promise<void> {
            try {
                this.data = await request("post", "login", "", this.contentType, logData);
                if(this.data?.status === SRV_STATUS.SUCCESS && this.data.content.verified) {
                    this.token = this.data.content.token;
                    await this.getUser();
                }
            } catch (error) {
                // TODO Handle errors here
                console.error("Error in login function:", error);
            }
        },
        async logout() {
            try {
                this.data = await request("post", "logout", this.token, this.contentType, '');
                if(this.data?.status === SRV_STATUS.SUCCESS) {
                    this.data = null;
                    this.authUser = basicState;
                    this.token = '';
                }
            } catch (error) {
                // TODO Handle errors here
            }
        },
        async register(registerData: any):Promise<void> {
            try {
                this.data = await request("post", "register", "", this.contentType, registerData);
                if(this.data?.status === SRV_STATUS.SUCCESS) {
                    this.token = this.data?.content.token;
                    // TODO Handle
                }
            } catch (error) {
                // TODO Handle errors
                console.error("Error in register function:", error);
            }
        },
        async getUser() {
            try {
                if(this.token) {
                    this.data = await request("get", "account/get", this.token, this.contentType, '');
                    this.authUser = this.data?.content;
                } else {
                    // TODO Rediriger vers la page de connexion
                }

            } catch(error) {
                // TODO Handle error here
                console.log("FONCTIONNE PAS");
            }
        },
        async activateAccount(code: string): Promise<void> {
            try {
                this.data = await request("post", "account/activate/verify/" + code, "", "", "");
                if(this.data?.status === SRV_STATUS.SUCCESS) {
                    console.log("Account activate");
                }
            } catch (error) {
                console.log("activeAccount - errors" + error);
            }
        },
        async updateUserAccount(data, url) {
            // TODO route api : /account/update/email => mail
            // /account/update/username => username
            // /account/update/password => password
        },
    }
});