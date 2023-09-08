let API_HOST = import.meta.env.VITE_API_HOST;
let API_PORT = import.meta.env.VITE_API_PORT;
let API_PROTOCOL = import.meta.env.VITE_API_PROTOCOL;

export const srv: {
    protocol: string,
    name: string,
    port: number,
    api: string
} = {
    protocol: API_PROTOCOL === undefined ? 'http' : API_PROTOCOL,
    name: API_HOST === undefined ? 'localhost' : API_HOST,
    port: API_PORT === undefined ? 8000 : API_PORT,
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

export const srvAddress: string = srv.protocol + "://" + srv.name + ":" + srv.port + "/" + srv.api + "/";

/**
 * The function `request` sends an HTTP request to a specified URL with the given method, token,
 * content type, and values, and returns a promise that resolves to the response.
 * @param {string} method - The HTTP method to be used for the request (e.g., "GET", "POST", "PUT",
 * "DELETE").
 * @param {string} url - The `url` parameter is a string that represents the endpoint or URL of the API
 * that you want to make a request to.
 * @param {string} token - The `token` parameter is a string that represents an authorization token. It
 * is used to authenticate the request to the server.
 * @param {string} contentType - The `contentType` parameter is a string that specifies the type of
 * content being sent in the request body. It is used to set the `Content-Type` header in the request.
 * Common values for `contentType` include `"application/json"`, `"application/x-www-form-urlencoded"`,
 * and `"multipart/form
 * @param {any} values - The `values` parameter is an object that contains the data to be sent in the
 * request body. It can be any valid JSON object.
 * @returns a Promise that resolves to an object of type IResponse.
 */
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

    if (token) {
        requestOptions.headers["Authorization"] = token;
    }

    if (values) {
        requestOptions.headers["Content-Type"] = contentType;
        requestOptions.body = values;
    }

    return fetch(srvAddress + url, requestOptions)
        .then((response: Response) => response.json())
        .catch((error): void => {
            console.log(error);
            throw error;
        });
}

