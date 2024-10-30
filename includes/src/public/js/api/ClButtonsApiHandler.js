class ClButtonsApiHandler {

    #API_URL

    constructor() {
        this.#API_URL = 'https://app.clicklease.com/jsdk';
        // this.#API_URL = 'http://localhost:3000/jsdk';
    }

    async getButton(reqBody) {
        const endpoint = '/reqAmount';
        // const { price, desc, btnName } = reqBody;
        return fetch(`${this.#API_URL}${endpoint}`, {
            body: JSON.stringify([{ maxAmount: 15000, ...reqBody }]),
            method: 'POST',
            headers: new Headers({ 'Content-Type': 'application/json' }),
            mode: 'cors'
        })
            .then(res => res.json())
            .catch(err => {
                console.log(err);
                return '';
            });
    }

    async getAllThemes() {
        const endpoint = '/plg/wp-btn/themes';
    }

    async getAllLightModes() {
        const endpoint = '/plg/wp-btn/lightMode';
    }

    async getBtnGroups(btnGrpArr = ['ALL']) {
        const params = btnGrpArr.map(btnGrpName => 'btnGrp=' + btnGrpName).join('&');
        const endpoint = `/plg/wp-btn/button?${params}`;
        return fetch(`${this.#API_URL}${endpoint}`, {
            headers: new Headers({ 'Content-Type': 'application/json' })
        }).then(res => res.json())
            .catch(err => {
                console.log(err)
                return [];
            });
    }

}