import { defineStore } from "pinia";
import userData from "./testUser.json";
import  userDataLogin from "./testLogin.json";

export const useUserStore = defineStore("user", {
    state: () => ({
        authUser: null as null | { username: string; email: string; is_verified: boolean },
        token: ''
    }),
    getters:{
        user: (state) => state.authUser
    },
    actions: {
        async getUser(): Promise<void> {
            /*const response = await fetch("https://localhost:3000/user");
            const user = await response.json();*/
            if(userData.status === 200)
            {
                await this.getToken();
                this.authUser = userData.content;
                console.log("FETCH USER");
            }
        },
        async login(email:string, password:string): Promise<void> {
            /*const response = await fetch("https://localhost:3000/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email, password }),
            });
            const user = await response.json()*/

            //this.user = user;
            if(userDataLogin.status === 200)
            {
                this.token = userDataLogin.content.token;
                await this.getUser();
                console.log("USER CONNECTED");
            }
        },
        async logout(): Promise<void> {
            this.authUser = null;
            this.token = "";
            console.log("USER DISCONNECT");
        },
        /*async register(username: string, email: string, password: string) {
            const response = await fetch("https://localhost:3000/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({username, email, password }),
            });
            const user = await response.json();
            this.user = user;
        },*/
        async getToken(): Promise<string> {
            return this.token;
        }
    },
});