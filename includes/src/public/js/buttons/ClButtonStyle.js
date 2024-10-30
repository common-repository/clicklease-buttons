class ClButtonStyle {

    #_btnsSimpleArr;
    #_btnsGroups;
    #btnsArrThemed;

    constructor(btnsGroupsJson = [BUTTONS_LIST.DESKTOP], { mode, theme }) {
        this._mode = mode || 'light';
        this._theme = theme || BUTTON_STYLE_TYPE.PRIM;
        this.#_btnsGroups = ['DESKTOP', 'MOBILE', 'NO_LEASE'];
        this.#_btnsSimpleArr = this.#flatToList(btnsGroupsJson);
        this.#btnsArrThemed = null;
    }

    static createButton({
        type = "btn",
        value = "",
        price = 5000,
        token = "00000a00-0000-0000-0000-000000000000",
        pMaxAMount = 15000,
        url = '' }) {
        const getWrappType = ({ eleType, innerEle, customLink }) => {
            let newClWrapp;
            switch (eleType) {
                case 'link':
                    newClWrapp = document.createElement('a');
                    newClWrapp.setAttribute('target', '_blank');
                    newClWrapp.classList.add('clLink', 'clEle');
                    newClWrapp.href = customLink;
                    break;
                case 'btn':
                    newClWrapp = document.createElement('button');
                    newClWrapp.classList.add('clBtn', 'clEle');
                    break;
                case 'wrapp':
                    newClWrapp = document.createElement('div');
                    newClWrapp.classList.add('clBtnShowcase', 'clEle', 'clBtn');
                    break;

                default:
                    getWrappType('wrapp');
            }
            newClWrapp.classList.add('clLink--show');
            if (innerEle instanceof Node) {
                newClWrapp.appendChild(innerEle);
            } else {
                newClWrapp.innerHTML += innerEle;
            }
            return newClWrapp;
        }
        return new ClButtonsApiHandler().getButton({
            price: price, // optional
            description: 'Some product description', // optional
            button: value,
            token: token,
            maxAmount: pMaxAMount
        }).then(res => {
            const { script } = res;
            let eleRes = '';
            if (!script) return eleRes;
            const svgContentWrapp = document.createElement('div');
            const eleHandlrs = ['div', 'img', 'svg', 'object'];
            eleHandlrs.every(eleToMap => {
                if (script.toLowerCase().includes(`<${eleToMap}`) && script.toLowerCase().includes(`${eleToMap}>`)) {
                    svgContentWrapp.innerHTML = `<${eleToMap}` + script.split(`<${eleToMap}`).pop().split(`</${eleToMap}>`).shift() + `</${eleToMap}>`;
                    eleRes = getWrappType({ innerEle: svgContentWrapp.firstChild.innerHTML, eleType: type, customLink: url });
                    return false;
                }
                return true
            })
            return eleRes;
        });
    }

    createThemeName(btnId) {
        const foundBtn = [...this.#_btnsSimpleArr].filter(btnOpts => btnOpts.value.toLowerCase().includes(btnId));
        let returnName = null;
        if (foundBtn.length) {
            // { {themes: dark: {'PRIM-primary_DARK',''}, light: {''}} }
            const btnThemedId = foundBtn[0].themes[this._mode].filter(thName => thName.includes(this._theme))[0];
            if (btnThemedId) {
                let btnThemeName = btnThemedId.replace(this._theme, '').substr(1);
                btnThemeName = btnThemeName.includes(':')
                    ? btnThemeName.replace('name', '').split(':').join('')
                    : btnThemeName.replace('name', btnId);
                returnName = btnThemeName;
            };
            // console.log(btnThemeName);
        }
        return returnName
    }

    #flatToList(btnsJSONArr) {
        if (btnsJSONArr instanceof Array) {
            return btnsJSONArr
                .map(grpObj => Object.entries(grpObj).map(entry => ({ ...entry.pop() })))
                .flatMap(grpBtnsArr => grpBtnsArr);

        }
        return [];
    }

    getList() {
        if (this.#btnsArrThemed) return this.#btnsArrThemed;
        this.#btnsArrThemed = this.#_btnsSimpleArr.map(btnOpt => {
            let res = null;
            const copyBtnOpt = { ...btnOpt, normalValue: btnOpt.value };
            const themedName = this.createThemeName(btnOpt.value);
            if (themedName) {
                copyBtnOpt.value = themedName;
                res = copyBtnOpt;
            };
            return res;
        }).filter(svgName => svgName);
        return this.#btnsArrThemed;
    }

    createSingleBtnEle(btnKey) {

    }

    setMode(newMode) {
        this.#btnsArrThemed = null;
        this._mode = newMode;
    }

    setTheme(newTheme) {
        this._theme = newTheme;
    }

}