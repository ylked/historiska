import {defineStore} from "pinia";
import { useLocalStorage } from "@vueuse/core"
import {request, SRV_STATUS, IResponse} from "./requests.ts";
import router from "../router";

const basicState = {
    username: '',
    email: '',
    is_verified: false,
    is_connected: false
};

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

        /**
         * Logs a user in using with login data. It resets the user's state and authenticate with a login request to the server.
         * If the login is successful, it sets the user's token and marks them as connected, then fetches user data.
         *
         * @param {any} logData - The login data provided by the user.
         */
        async login(logData:any): Promise<void> {
            this.reset();
            try {
                this.data = await request("post", "login", "", this.contentType, logData);
                if(this.data?.status === SRV_STATUS.SUCCESS) {
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

        /**
         * Logs out the current user. If the user's token is no longer valid,
         * it redirects them to the login page. Otherwise, it sends a logout request
         * to the server and resets the user's state upon successful logout.
         */
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

        /**
         * Registers a user with the registration data.
         * It resets the current user's state, sends a registration request to the server,
         * and updates the user's token and account information upon successful registration.
         *
         * @param {any} registerData - The user's registration data (JSON) {"username": "", "email": "", "password": ""}.
         */
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

        /**
         * Fetches the user's account information if a valid token is available.
         * If the token is invalid or missing, it redirects to the "Connexion" page.
         */
        async fetchUser(): Promise<void> {
            if(await this.isValidToken() === false) {
                await router.push({name: "Connexion"});
            } else {
                try {
                    if (this.token) {
                        this.data = await request("get", "account/get", this.token, this.contentType, '');
                        this.authUser = this.data?.content;
                    }
                } catch (error) {
                    console.log("Error in fetchUser function : " + error);
                }
            }
        },

        /**
         * Activates the user's account by verifying the provided activation code.
         * If successful, it fetches the user's updated account information.
         *
         * @param {string} code - The activation code to verify the user's account.
         */
        async activateAccount(code: string): Promise<void> {
            try {
                this.data = await request("post", "account/activate/verify/" + code, "", "", "");
                if (this.data?.status === SRV_STATUS.SUCCESS) {
                    await this.fetchUser();
                }
            } catch (error) {
                console.log("activeAccount - errors" + error);
            }
        },

        /**
         * Resends an activation request to the user's account to verify their identity.
         * If the user's token is invalid, they will be redirected to the login page.
         */
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

        /**
         * Updates the user's account information by sending a request.
         *
         * @param {any} data - The data to update the user's account.
         * Can be email in format JSON {"email": "<new e-mail>"}
         * Can be username in format JSON {"username": "<new username>"}
         * Can be password in format JSON {"password": "<new password>"}
         * @param {string} url - The URL to send the update request to.
         * For email : /account/update/email
         * For username : /account/update/username
         * For password : /account/update/password
         */
        async updateUserAccount(data:any, url:string): Promise<void> {
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

        /**
         * Checks the validity of the authentication token associated with the user.
         *
         * @returns {boolean|null} Returns `true` if the token is valid, `false` if invalid, or `null` if an error occurs.
         */
        async isValidToken(): Promise<any> {
            try {
                if(this.token) {
                    this.data = await request("get", "token/check", this.token, "", "");
                    if(this.data?.status === SRV_STATUS.SUCCESS) {
                        this.authUser.is_connected = this.data?.content.valid === true;
                        return this.data?.content.valid;
                    }
                }
            } catch (errors) {
                console.log("Errors in isValidToken : " + errors);
            }
        },

        /**
         * Initiates a forgot password request for the specified user identifier (username or email).
         *
         * @param {string} userid - The user identifier for which the forgot password request is made (JSON) {"id": "<username> or <e-mail>"}
         * @throws {Error} Throws an exception if an error occurs during the forgot password request.
         */
        async forgotPassword(userid: string): Promise<void> {
            try {
                this.data = await request("post", "forgot", "", this.contentType, userid);
            } catch (error) {
                console.log("Errors in forgotPassword");
            }
        },

        /**
         * Initiates a password recovery request by sending the specified recovery data.
         *
         * @param {any} userData - {"token": "<temporary recovery token>", "password": "<new password>"}
         * @throws {Error} Throws an exception if an error occurs during the password recovery request.
         */
        async recoveryPassword(userData:any): Promise<void> {
            try {
                this.data = await request("post", "recover", "", this.contentType, userData);
            } catch (error){
                console.log("Errors in forgotPassword");
            }
        },

        /**
         * Reset user to initial state
         */
        reset(): void {
            this.authUser = basicState;
            this.authUser.is_connected = false;
            this.token = '';
            this.data = null;
        }
    }
});