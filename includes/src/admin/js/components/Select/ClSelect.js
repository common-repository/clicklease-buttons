//@param type this value can (modal/normal) to render a select fiedl with a list or a modal
const ClSelect = function (slcId, type = 'normal', options = [], pModalOpts = {}) {

    let inStyleOptsEle;

    const init = () => {
        // console.log(options);
        inStyleOptsEle = document.querySelector(`#${slcId}`);
        modalOpts = {
            tlt: 'Select an option',
            desc: 'Press over the option you want. Then, click save button at the button',
            cardSize: 'fixed',
            customContent: {
                body: '',
                position: 'top',
                ...pModalOpts.customContent,
            },
            ...pModalOpts,
            ele: null,
        };
    }


    const createElement = (type) => {
        if (type === 'modal') {
            const { tlt, desc, cardSize, customContent } = modalOpts;
            // If arr has options, those are used but if it has not uses select default ones
            const btnsTypeListComp = BtnsStyleList({
                arrOpts: (options?.length && options) || inStyleOptsEle.options,
                cardsSize: cardSize,
                defaultValue: modalOpts?.defValue || null
            });
            // .setDarkMode(true)
            const btnsTypeListEle = btnsTypeListComp.build();
            let modalBody = btnsTypeListEle;
            if (customContent.body) {
                const divCustomContentEle = document.createElement('div');
                const divWrappCustomContentEle = document.createElement('div');
                divCustomContentEle.classList.add('cl-select-custom-content');
                divWrappCustomContentEle.appendChild(customContent.body);
                if (customContent.position === 'top') {
                    divCustomContentEle.appendChild(divWrappCustomContentEle);
                    divCustomContentEle.appendChild(btnsTypeListEle);
                } else {
                    divCustomContentEle.appendChild(btnsTypeListEle);
                    divCustomContentEle.appendChild(divWrappCustomContentEle);
                }
                modalBody = divCustomContentEle;
            }
            let btnStylesMl = ClModal('cl-modal-' + slcId,
                tlt,
                desc,
                modalBody,
                [
                    {
                        lbl: 'Choose',
                        style: 'prim--lg',
                        onclick: () => {
                            btnStylesMl.toggle();
                            changeBtnStyle(btnsTypeListComp.getActiveVal())
                        }
                    },
                    {
                        lbl: 'Cancel',
                        style: 'sec-outline-lg',
                        onclick: function () { btnStylesMl.toggle() }
                    },
                ]);
            const modalEle = btnStylesMl.build();
            modalOpts.ele = modalEle;
            document.querySelector('#wpwrap').appendChild(modalEle);
            inStyleOptsEle.addEventListener('mousedown', (e) => {
                e.preventDefault();
            });
            inStyleOptsEle.onclick = () => {
                inStyleOptsEle.blur();
                btnStylesMl.toggle();
            }
        }
        return inStyleOptsEle;
    }

    const changeBtnStyle = (typeValue) => {
        console.log(typeValue);
        const selectedStyle = typeValue;
        const styleOpts = [...options];
        let selectedOp = [...inStyleOptsEle.options].filter(op => typeValue.includes(op.value));
        // every options are false
        styleOpts.forEach(opEle => {
            opEle.selected = false;
        });
        if (selectedOp.length > 0) {
            selectedOp[0].selected = true;
            inStyleOptsEle.onchange(inStyleOpts);
        }
    }

    const switchTheme = (themeOpts) => {
        const { isDark } = themeOpts;
        if (isDark) modalOpts.ele.classList.add('dark');
        else modalOpts.ele.classList.remove('dark');
    }

    const setDarkMode = function (enable = false) {
        if (enable) {
            // ThemeManager.subscribe((themeOpts) => switchTheme(themeOpts));
        }
        return this;
    }

    const setOptions = (newOptionsArr) => {
        if (newOptionsArr?.length && newOptionsArr) {
            if (type === 'modal') {
                const { cardSize } = modalOpts;
                const btnsTypeListEle = BtnsStyleList({
                    arrOpts: newOptionsArr,
                    cardSize: cardSize
                }).build();
                let crrList = modalOpts.ele.querySelector('*[class*=card-list]');
                crrList.parentElement.insertBefore(btnsTypeListEle, crrList);
                crrList.remove();
                // document.body.appendChild();
                // console.log();
            }
        }
    }

    const build = async () => {
        return createElement(type);
    }
    init();
    return { build, setOptions, setDarkMode };
}