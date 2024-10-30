class ClButtonSettings {

    #externalProps;
    #initStylesArr;
    #innerEles
    #btnStyleServ
    #themesList;
    #modeList;
    #designSettings;
    // @param btnsOptions formatt must be simple. btn_8 not btn_8_prim_dark
    // @param can only be desltop or mobile
    constructor(id, mode, theme, darkMode, btnsOptions, externalProps = null) {
        this._id = id;
        this._mode = mode;
        this._theme = theme
        this._darkMode = darkMode;
        this._btnsOptions = btnsOptions; // repeated

        this.#initStylesArr = [theme, darkMode];
        this.#externalProps = {
            ...externalProps,
            token: '00000000-0000-0000-0000-000000000000'
        };
        this.#innerEles = [];
        this.#btnStyleServ = new ClButtonStyle(this.#externalProps.btnsList,
            { mode: this._darkMode, theme: this._theme });
        // this.#designSettings = ['light:PRIM', 'dark:PRIM', 'light:SEC', 'dark:SEC', 'light:TER', 'dark:TER'];
        this.#themesList = [
            {
                value: 'PRIM',
                bg: '#1868c3',
                color: '#ffffff'
            },
            {
                value: 'SEC',
                bg: '#747474',
                color: '#ffffff'
            },
            {
                value: 'TER',
                bg: '#00adee',
                text: '#ffffff',
                outline: true
            }
        ];
        this.#modeList = [
            {
                value: 'dark',
                label: 'Dark'
            },
            {
                value: 'light',
                label: 'Light'
            },
            {
                value: 'solar',
                label: 'solarized'
            }
        ];

        this.#designSettings = this.createOptionsDesign(this.#modeList, this.#themesList, this.#externalProps.btnsList)
    }


    updateElments(crrThemeOpts) {
        const { theme, isDark } = crrThemeOpts[this._mode];
        const [crrBtnListEle] = [this.#innerEles[this.#innerEles.length - 1]]; // gete crr list ele is shown 
        setTimeout(() => {
            // if (theme && this._theme && theme !== this._theme) {
            this.#setTheme(theme);
            // }
            this.#setDarkMode(isDark ? 'dark' : 'light');
            if (isDark !== (this._darkMode === 'dark')
                && theme && this._theme && theme !== this._theme) {
                // console.log('reload a list with the new themes');
                // console.log(`theme: ${this._theme}`, 'dark mode: ' + this._darkMode);
            }
            this.#btnStyleServ.setTheme(this._theme);
            this.#btnStyleServ.setMode(this._darkMode);

            this.#changeThemeEles(this._darkMode);
            this.#creataBtnList()
                .then(newBtnsList => {
                    // console.log('%cupdateElments: ', 'background: blue;', newBtnsList);
                    if (!newBtnsList.noBtns) {
                        crrBtnListEle.parentElement.appendChild(newBtnsList);
                        crrBtnListEle.remove();
                        this.#innerEles.pop();
                        this.#innerEles.push(newBtnsList);
                        return;
                    }
                    // restore theme values
                    this.#changeThemeEles('light');
                    ThemeManager.switch(this._mode, { newDarkMode: 'light', newTheme: 'PRIM' });
                });
        }, 80);
        console.log(this._mode, this._darkMode, this._theme);
    }

    #createDarkModeRadioFlds() {
        //     this.#modeList.filter(({ value }) => this.#designSettings[value].length));
        const radioBoxDarkModeEle = ClRadioFdl(`clk_op_dark_mode_${this._mode}`, 'Theme: ',
            this.#modeList.filter(({ value }) => this.#designSettings[value].length),
            ({ target }) => {
                this.#changeThemeEles(target.value);
                ThemeManager.switch(this._mode, { newDarkMode: target.value, newTheme: this._theme })
            },
            'radio',
            this._darkMode
        ).build();
        this.#innerEles.push(radioBoxDarkModeEle);
        return radioBoxDarkModeEle;
    }

    #createThemeRadioflds() {
        const radioFldsOpts = this.#themesList.map(themeSttgs => ({
            value: themeSttgs.value,
            customStyle: {
                customCssClasses: 'custom' || themeSttgs.value.toLowerCase(),
                bg: themeSttgs.bg,
                color: themeSttgs.color,
                outline: themeSttgs.outline || false
            }
        }))
        const themesByLightModeArr = Object.fromEntries(
            Object.entries(this.#designSettings)
                .map(([kMode, thmArr]) => [kMode,
                    radioFldsOpts.filter(thOpts => thmArr.find(name => thOpts.value === name))
                ])
        );
        // creates thmes radio fields by lighting mode
        const radioThemeEles = Object.keys(themesByLightModeArr).map(lmode => {
            const radioThEle = ClRadioFdl(`clk_op_theme_${this._mode}`, 'Button Color: ',
                themesByLightModeArr[lmode],
                ({ target }) => {
                    ThemeManager.switch(this._mode, { newTheme: target.value })
                },
                'button',
                this._theme
            ).build();
            radioThEle.classList.add(`cl-radio-${this._mode}-${lmode}`);
            if (lmode !== this._darkMode) {
                radioThEle.classList.add('cl-fld--hidden');
            } else this.#innerEles.push(radioThEle);
            return radioThEle
        })
        return radioThemeEles;
    }

    async #creataBtnList() {
        const listTlt = document.createElement('h4');
        listTlt.textContent = 'Choose Button Style';
        // set default value in list
        const getNormalSvgId = (pBtnsList) => pBtnsList.find((opt) => opt.value === this.#externalProps.activeSvg);
        const normalSvgId = document.querySelector(`#${this.#externalProps.mdlId}`)?.getAttribute('cl-choosen-btn-normal');
        // 
        // console.log(this.#innerEles);
        this.#disableControls();
        return this.#mapToCardsList(this.#btnStyleServ.getList())
            .then(resSvgsList => {
                if (normalSvgId) {
                    let crrBtnOpt = resSvgsList.find((opt) => opt.normalValue === normalSvgId)
                    if (crrBtnOpt) {
                        document.querySelector(`#${this.#externalProps.mdlId}`).setAttribute('cl-choosen-btn', crrBtnOpt.value);
                        this.#externalProps.activeSvg = crrBtnOpt.value;
                    }
                }
                if (!resSvgsList.length) {
                    let err = new Error(`No buttons were found for: ${this._darkMode} and ${this._theme}`);
                    err.noBtns = true;
                    throw err;
                };
                return BtnsStyleList({
                    arrOpts: [],
                    cardsSize: 'content',
                    defaultValue: this.#externalProps.activeSvg,
                    searchRes: true, // REMOVE this probably
                    btnsList: resSvgsList,
                    onClick: (ev) => {
                        console.log('Card must be active and element in viewer should change');
                        const { value, listWrapp } = ev;
                        // change active value on 
                        this.#externalProps.activeSvg = value;
                        document.querySelector(`#${this.#externalProps.mdlId}`).setAttribute('cl-choosen-btn', ev.value);
                        document.querySelector(`#${this.#externalProps.mdlId}`).setAttribute('cl-choosen-btn-normal', getNormalSvgId(resSvgsList).normalValue);
                    }

                }).buildAsync();
            }).then(resUlElewithSvgs => {
                this.#innerEles.push(resUlElewithSvgs);
                setTimeout(() => {
                    this.#disableControls(false);
                }, 2500);
                return resUlElewithSvgs;
            }).catch(err => {
                console.log('%cError: ', 'background: #DE1717;', err.message);
                console.log('%cError: ', 'background: #DE1717;', err.noBtns);
                return err;
            });
    }

    async #mapToCardsList(slctOpts) {
        // const clBtnApiHandlr = new ClButtonsApiHandler();
        // const svgsFetched = 
        return Promise.all(
            slctOpts.map(async ({ value, label, normalValue }) => ({
                label: label,
                value: value,
                normalValue: normalValue,
                content: await ClButtonStyle.createButton({
                    type: "wrapp",
                    value: value,
                    price: 7000,
                    token: this.#externalProps.token,
                    url: document.querySelector('#clk_op_redurl').value
                }).then(res => {
                    return res;
                })
                    .catch(err => {
                        console.log(err.message);
                        return document.createElement('div');
                    }),
            }))
        );
        // return slctOpts
    }

    build() {
        // create dark mode options
        // create theme options
        // create list view options
        const wrapper = document.createElement('div');
        wrapper.id = this._id;
        wrapper.classList.add('cl-viewer__modalBody');
        wrapper.style = 'height: 100%; width: 100%;';
        [this.#createDarkModeRadioFlds(),
        ...this.#createThemeRadioflds(),
        this.#creataBtnList()
        ].forEach(async ele => {
            wrapper.appendChild(await ele);
        });
        // ThemeManag er.subscribe(this._mode, function (globalThemeOpts) { this.wrapperupdateElments.call(this, globalThemeOpts); });
        ThemeManager.subscribe(this._mode, (globalThemeOpts) => { this.updateElments(globalThemeOpts); });
        return wrapper;
    }

    createOptionsDesign(modeList, themesList, buttonsList) {
        // creates an object with modes which contain all the themes
        let initDesignSettgs = Object.fromEntries(
            modeList.map(modeStt => [[modeStt.value], themesList.map(thStt => thStt.value)])
        );
        // puts in an array every buttons themes
        const plgThemesConfig = buttonsList
            .map(btnList => Object.keys(btnList).map(k => btnList[k].themes))
            .flatMap(b => b);
        // it requires at least one button theme to render color theme option
        initDesignSettgs =
            Object.entries(initDesignSettgs).map(([kmode, modeThms]) => {
                return [kmode, modeThms.filter(thName => plgThemesConfig
                    .filter(btnObj => btnObj[kmode] && btnObj[kmode].find(btnThName => btnThName.includes(thName))).length
                )];
            });
        return Object.fromEntries(initDesignSettgs);
    }

    #changeThemeEles(crrLightingMode) {
        const crrRdFldBox = this.#innerEles[1];
        if (!crrRdFldBox.className.includes(crrLightingMode)) {
            crrRdFldBox.classList.add('cl-fld--hidden');
            crrRdFldBox.parentElement.querySelectorAll('.cl-radios-wrapp')
                .forEach(rdFldBox => {
                    if (rdFldBox.className.includes(crrLightingMode)) {
                        rdFldBox.classList.remove('cl-fld--hidden')
                        this.#innerEles[1] = rdFldBox;
                    }
                });
        }
    }

    #disableControls(disable = true) {
        if (this.#innerEles.length) {
            this.#innerEles[0].querySelectorAll('input').forEach(ele => ele.disabled = disable);
            this.#innerEles[1].querySelectorAll('button').forEach(ele => ele.disabled = disable);
            // if(disable) {
            //     this.#innerEles[1].classList.add('');
            // } else {

            // }
        }
    }

    set theme(newTheme) {
        this._theme = newTheme;
        // update theme radio buttons state and its relates
    }

    #setDarkMode(newDarkMode) {
        this._darkMode = newDarkMode;
        // update dark mode radio buttons state
        document.querySelector(`input[id*=clk_op_dark_mode_${this._mode}]`).value = this._darkMode;
        document.querySelector(`input[id*=clk_op_dark_mode_${this._mode}]`).setAttribute('value', this._darkMode);
        this.#innerEles[0].querySelector(`input[type="radio"][value*=${this._darkMode}]`).checked = true;
    }

    #setTheme(newTheme) {
        if (typeof newTheme === 'string') this._theme = newTheme;
        const crrActiveThemes = this.#innerEles[1].querySelector(`button[value*=${this._theme}]`);
        // 
        document.querySelector(`input[id*=clk_op_theme_${this._mode}]`).value = this._theme;
        document.querySelector(`input[id*=clk_op_theme_${this._mode}]`).setAttribute('value', this._theme);
        [...this.#innerEles[1].querySelectorAll(`button`)].forEach(btnEle => btnEle.classList.remove('cl-ratio-btn--active'));
        if (crrActiveThemes) crrActiveThemes.classList.add('cl-ratio-btn--active');
        // }, 1000);
    }

    isDarkMode = (glbMode = 'dark') => this._darkMode === glbMode;

}