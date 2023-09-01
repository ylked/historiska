export const srv: {
    protocol: string,
    name: string,
    port: number,
    api: string
} = {
    protocol: 'http',
    name: 'localhost',
    port: 8000,
    api: 'api'
}

export const SRV_STATUS: {
    SUCCESS: number,
    BAD_REQUEST: number,
    FORBIDDEN: number,
    NOT_FOUND: number,
    TOO_MANY_REQUESTS: number,
    INTERNAL_ERROR: number
} = {
    SUCCESS: 200,
    BAD_REQUEST: 400,
    FORBIDDEN: 403,
    NOT_FOUND: 404,
    TOO_MANY_REQUESTS: 429,
    INTERNAL_ERROR: 500
}

export interface IResponse {
    success: boolean,
    status: number,
    error: string,
    message: string,
    content?: any
}

export const srvAddress:string = srv.protocol + "://" + srv.name + ":" + srv.port + "/" + srv.api + "/";

export function request(method: string, url: string, token: string, contentType: string, values: any): Promise<IResponse> {
    const requestOptions: {
        method: string;
        headers: {
            [key: string]: string;
        };
        body?: string;
    } = {
        method: method.toUpperCase(),
        headers: {}
    };

    if(token) {
        requestOptions.headers["Authorization"] = token;
    }

    if (values) {
        requestOptions.headers["Content-Type"] = contentType;
        requestOptions.body = values;
    }

    return fetch(srvAddress + url, requestOptions)
        .then((response:Response) => response.json())
        .catch((error):void => {
            console.log(error);
            throw error;
        });
}

