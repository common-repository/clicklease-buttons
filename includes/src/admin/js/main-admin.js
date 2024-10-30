let elesViewer;
let ThemeManager = {}

const registerLandingFunct = () => {
    const activeLanding = (e) => {
        const fldRedirect = document.querySelector('#clk_op_redurl');
        const defaultLink = 'https://app.clicklease.com/inlineapp?token=';
        let targetEle = e ? e.target : document.querySelector('#clk_op_has_landing');
        if (targetEle.checked) {
            fldRedirect.parentElement.parentElement.style = 'display: table-row';
            console.log(fldRedirect.getAttribute('value').toLowerCase().includes('defaultLink'),);
            if (fldRedirect.getAttribute('value').toLowerCase().includes(defaultLink))
                fldRedirect.setAttribute('value', '');
        } else {
            const crrTokenVal = document.querySelector('input[id=clk_token]') || '';
            fldRedirect.parentElement.parentElement.style = 'display: none';
            fldRedirect.setAttribute('value', `${defaultLink}${crrTokenVal.value}`);
        }
    }
    document.querySelector('#clk_op_has_landing').onchange = activeLanding;
    activeLanding();
}

const registerStylesFunct = () => {
    const maxRangeFld = document.querySelector('#clk_op_max_price');
    maxRangeFld.parentElement.style = '--cl-fld-icon: "$"';
    maxRangeFld.parentElement.classList.add('cl-fld--pre-icon');
}

const registerSelectPositionFunct = () => {
    const fldSlctPos = document.querySelector('#clk_op_position');
    const selectPagePosFld = ClSelect(fldSlctPos.id, 'modal',
        [...fldSlctPos.options].map(opEle => ({ value: `btn-position/${opEle.value}`, label: opEle.label })),
        {
            tlt: 'Select button position in product page',
            desc: 'Click the option where you want to show the button and then save the changes',
            cardSize: 'fixed-3',
            defValue: `btn-position/${fldSlctPos.value}`
        });
    selectPagePosFld.build();
}


const chooseBtnPos = (info, e) => {
    const { element, value } = info;
    [...document.querySelectorAll('#btn-sclt-pos-modal .cl-card-item')]
        .forEach(ele => {
            ele.classList.remove('cl-btn-card-item--active');
        });
    element.parentElement.classList.add('cl-btn-card-item--active');
    document.querySelector('#btn-sclt-pos-modal').setAttribute('cl-op-selected', value);
}

const changeBtnPos = (mdlInst) => {
    const newSelectVal = document.querySelector('#btn-sclt-pos-modal').getAttribute('cl-op-selected');
    const btnPosOpts = [...document.querySelector('#clk_op_position').options];
    btnPosOpts.forEach(opEle => {
        opEle.selected = false;
        if (String(opEle.value) === newSelectVal) opEle.selected = true;
    });
    mdlInst.toggle();
}

const renderBtnViewer = async () => {
    const btnApiHandlr = new ClButtonsApiHandler();
    let fldBtnType = document.querySelector('#clk_op_btn_style_desktop');
    let fldBtnTypeMobile = document.querySelector('#clk_op_btn_style_mobile');
    const fldBtnWrapp = [...document.querySelectorAll('form[class=cl-stt] table')].pop();
    const getGrp = (grpObj) => Object.entries(grpObj).map(([_, arr]) => arr);
    const btnsListDeskArr = await btnApiHandlr.getBtnGroups(['desktop'])
    const btnsListMobileArr = await btnApiHandlr.getBtnGroups(['mobile'])
    // console.log('%cmail-admin: ', 'background: red;', btnsListDeskArr.body);
    // console.log('%cmail-admin: ', 'background: red;', btnsListMobileArr.body);

    elesViewer = ElementsViewer([
        {
            id: 'cl-button-viewer',
            btnType: fldBtnType.value,
            lbl: 'Desktop: ',
            elesOpts: BUTTON_TYPE.NORMAL,
            btnStyle: 'desktop',
            btnsList: getGrp(btnsListDeskArr.body)
        },
        {
            id: 'cl-button-viewer-mobile',
            btnType: fldBtnTypeMobile.value,
            lbl: 'Mobile',
            elesOpts: BUTTON_TYPE.MOBILE,
            btnStyle: 'mobile',
            tooltipTxt: 'Mobile options is used on mobile devices and on store category pages',
            btnsList: getGrp(btnsListMobileArr.body)
        }
    ]);
    // this id values is btn type if a different one is sent
    // svg in viewer is changes
    // const reloadSvg = (id, crrStyles) => btnViewr.changeSvg(id, crrStyles);
    fldBtnType.onchange = function (target) {
        // console.log(target.value);
    };

    const eleViewerEle = await elesViewer.build();
    fldBtnWrapp.parentElement.insertBefore(eleViewerEle, fldBtnWrapp);
    setTimeout(() => fldBtnType.onchange(fldBtnType), 500);
}

const AdminSettings = function () {

    const state = {
        formEss: {
            isInCart: document.querySelector('#clk_op_is_in_cart'),
            cartPos: document.querySelector('#clk_cart_pos')
        }
    };

    // Show/hide cart position select field depending on 'Show in Cart' checkbox value
    const registerToggleCartPos = () => {
        const {
            formEss: { cartPos, isInCart }
        } = state;
        const setCartFieldStatus = () => {
            const fldWrapp = cartPos.parentElement.parentElement.parentElement;
            if (isInCart.checked)
                fldWrapp.classList.remove('cl-fld--hidden');
            else
                fldWrapp.classList.add('cl-fld--hidden');
        }
        isInCart.onchange = setCartFieldStatus;
        setCartFieldStatus();
    }

    const registerBtnPosCartFunct = () => {
        const fldCartPos = document.querySelector('#clk_cart_pos');
        const selectCartPosEle = ClSelect(fldCartPos.id, 'modal',
            [...fldCartPos.options].map(opEle => ({ value: `btn-position/cart/${opEle.value}`, label: opEle.label })),
            {
                tlt: 'Select button position in shopping cart page',
                desc: 'Click on the option where you want to show the button and then save the changes',
                cardSize: 'fixed-2', // when fixed the second number is numer of columns
                defValue: `btn-position/cart/${fldCartPos.value}`

            });
        selectCartPosEle.build();
    }

    const load = () => {
        registerToggleCartPos();
        registerBtnPosCartFunct();
    }

    return { load };
}

const registerNumFldsFunct = () => {
    document.querySelectorAll('input[class*=cl-fld][type=number]').forEach(numFld => {
        numFld.oninput = (e) => {
            let { valueAsNumber, value } = e.target;
            // console.log(e.target);
            //  if not a number set a empty string
            if (valueAsNumber || valueAsNumber === 0) {
                // if lesser than 0 sets an empty string
                if (valueAsNumber < 0) {
                    numFld.value = null;
                    return;
                }
                // avoids typing  trailing numbers
                if (value.length > 1) numFld.value = value.replace(/^0+/, '');
            } else numFld.value = '';
        }
    });
}

window.addEventListener('load', (e) => {
    ThemeManager = {
        crrOpts: {
            desktop: {
                isDark: document.querySelector('#clk_op_dark_mode_desktop').value === 'dark',
                theme: document.querySelector('#clk_op_theme_desktop').value || BUTTON_STYLE_TYPE.PRIM
            },
            mobile: {
                isDark: document.querySelector('#clk_op_dark_mode_mobile').value === 'dark',
                theme: document.querySelector('#clk_op_theme_mobile').value || BUTTON_STYLE_TYPE.PRIM
            }
        },
        subscribers: { desktop: [], mobile: [] },
        subscribe: function (vw, cb) {
            this.subscribers[vw].push(() => cb(this.crrOpts));
        },
        unsubscribe: function () {
            // remove someway
        },
        switch: function (op, { newDarkMode, newTheme }) {
            const { isDark, theme } = this.crrOpts[op];
            if (newTheme) this.crrOpts[op].theme = theme !== newTheme ? newTheme : theme
            if (newDarkMode && newDarkMode === 'light' || newDarkMode === 'dark')
                this.crrOpts[op].isDark = newDarkMode === 'dark'
            this.notify(op);
        },
        notify: function (vw) {
            this.subscribers[vw].forEach(subCb => subCb())
        }
    }
    registerStylesFunct();
    registerLandingFunct();
    registerSelectPositionFunct();
    renderBtnViewer();
    AdminSettings().load();
    registerNumFldsFunct();
});