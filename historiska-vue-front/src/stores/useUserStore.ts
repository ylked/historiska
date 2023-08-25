import {defineStore} from "pinia";

export default defineStore("user", {
    state: ():any => ({
        user: null,
        token: ''
    }),
    actions:{
        async fetchUser(){
            const response:Response = await fetch("/account/get");
            this.user = await response.json();
        },
        async login(username:String, password:String){
            const response:Response = await fetch("/login",{
                method: "POST",
                headers:{
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ username, password })
            });

            this.user = await response.json();
            this.token = this.user.content.token;
        },
        async register(username:String, email:String, password:String) {
            const response: Response = await fetch("/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ username, email, password }),
            });

            this.user = await response.json();
        }
    }
});