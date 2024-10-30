const ClButtonsViewer = (id, svgId,
    {
        lbl: lblText = 'Viewer: ',
        tooltipTxt = null,
        elesOpts: pElesOpts = [],
        btnStyle = 'desktop',
        flds,
        btnsList
    }) => {

    let viewerOptsState = {
        id: id || '',
        svgId: svgId || 'btn_8',
        lblText: lblText,
        leaseVal: 350,
        btnStyle: btnStyle,
        tooltipTxt: tooltipTxt,
        elesOpts: pElesOpts,
        instance: null,
        eles: [],
    }


    const changeElesTheme = () => {
        viewerOptsState.eles.forEach(ele => {
            if (ThemeManager.crrOpts[btnStyle].isDark) ele.classList.add('dark');
            else ele.classList.remove('dark');
        });
    }

    const updateElement = async () => {
        // const { btnStyle, svgId, eles } = viewerOptsState;
        const viewerEle = viewerOptsState.eles[1].querySelector('.cl-btn-viewer__body');
        const clBtntEle = await fetchBtn(viewerOptsState.svgId);
        viewerEle.innerHTML = '';
        viewerEle.appendChild(clBtntEle);
        // instance.updateElement(getBtnNameThemed());
    }

    const updateBtnFldAndViewer = (newStyleOpts) => {
        const { value, e = null, listWrapp = null } = newStyleOpts;
        // update viewer
        const originalSvgId = value.split('_').filter((nameChunk, i) => i < 2).join('_');
        viewerOptsState.svgId = value;
        updateElement();
        // update svg current real hidden field for either desktop and mobile
        const realFld = document.querySelector(`#clk_op_btn_style_${btnStyle}`);
        realFld.value = value;
        realFld.setAttribute('value', value);
        //  saved changes done on modal settings
        flds.darkMode = document.querySelector(`#clk_op_dark_mode_${btnStyle}`).value;
        flds.theme = document.querySelector(`#clk_op_theme_${btnStyle}`).value;
    }

    // TODO: this could be an aside component
    const createLbl = (opts) => {
        const { id, txt, icon, fldFor } = opts;
        const fldRelated = document.querySelector(`input[name=${fldFor}]`);
        const lblEle = document.createElement('label');
        lblEle.textContent = txt;
        lblEle.id = id;
        if (fldRelated) lblEle.setAttribute('for', fldFor);
        if (icon) {
            // TODO: side could be a choosable option
            const iconEle = document.createElement('i');
            iconEle.innerHTML = `<object type="image/svg+xml" data="${findSvg(icon.dir)}"></object>`;
            lblEle.appendChild(iconEle);
        };
        return lblEle;
    }

    const updateOptsOnThemeChange = (crrThemeppts) => {
        // change viewer bg
        changeElesTheme();

        // find equvalent version of the button
        // If active button in viewer is btn_prim and switches to dark, btn_prim_dark must be
        // saved as the current active btn type
        const vwMdl = document.querySelector('#cl-modal-' + viewerOptsState.id);
        let originalSvgId = vwMdl.getAttribute('cl-choosen-btn');
        vwMdl.setAttribute('cl-choosen-btn', originalSvgId);


        // updates svg is shown in viewer
        updateElement();
    }

    const createModalOpts = (tlt, desc, bodyEle) => {
        const btnsSettingsCompt = new ClButtonSettings(`cl-btn-stt-${btnStyle}`, btnStyle,
            flds.theme, flds.darkMode, pElesOpts, {
            activeSvg: viewerOptsState.svgId,
            mdlId: 'cl-modal-' + viewerOptsState.id,
            btnsList: btnsList
        });
        const btnStyleServ = new ClButtonStyle(btnsList,
            { mode: flds.darkMode, theme: flds.theme });
        // 
        bodyEle = btnsSettingsCompt.build();
        const mdlInstance = ClModal('cl-modal-' + viewerOptsState.id,
            tlt,
            desc,
            bodyEle,
            [
                {
                    lbl: 'Save Changes',
                    style: 'prim--lg',
                    onclick: () => {
                        updateBtnFldAndViewer({
                            value: document.querySelector('#cl-modal-' + viewerOptsState.id).getAttribute('cl-choosen-btn')
                        });
                        mdlInstance.toggle();
                    }
                },
                {
                    lbl: 'Cancel',
                    style: 'sec--outline-lg',
                    onclick: function () {
                        // should restore theme fld
                        // should restore dark mode fld
                        // compares againts init values
                        const crrDarkMode = ThemeManager.crrOpts[btnStyle].isDark !== (flds.darkMode === 'dark');
                        const crrTheme = ThemeManager.crrOpts[btnStyle].theme !== flds.theme;
                        if (crrDarkMode || crrTheme) {
                            console.log('CANCEL: ', flds.theme, flds.darkMode);
                            ThemeManager.switch(btnStyle, { newDarkMode: flds.darkMode, newTheme: flds.theme });
                        }
                        mdlInstance.toggle();
                    }
                }
            ]);
        const mdlEle = mdlInstance.build();
        // sets modal attrs init values
        mdlEle.firstElementChild.setAttribute('cl-choosen-btn', svgId);
        mdlEle.firstElementChild.setAttribute('cl-choosen-btn-normal', btnStyleServ.getList()
            .find((opt) => opt.value === viewerOptsState.svgId)?.normalValue);
        viewerOptsState.eles.push(mdlEle);
        document.querySelector('#wpwrap').appendChild(mdlEle);
        ThemeManager.subscribe(btnStyle, updateOptsOnThemeChange);
        return mdlInstance;
    }

    const createViewer = (innerEle) => {
        const vwWrapp = document.createElement('div'); // viewer wrappers
        const vwWrappBody = document.createElement('div');
        const mdlInst = createModalOpts(`${btnStyle[0].toUpperCase() + btnStyle.substring(1)} Button Design`, '',);
        const btnEle = ClAppButton(
            `cl-btn-${btnStyle}-vw`,
            [{
                type: 'click', cb: () => mdlInst.toggle()
            }],
            {
                btnType: 'a',
                lbl: 'Edit Button',
                styleType: 'prim-lg',
            }).build();
        const vwLbl = createLbl({
            id: 'lbl-' + viewerOptsState.id,
            txt: viewerOptsState.lblText,
            fldFor: 'clk_op_btn_style',
            icon: tooltipTxt ? { dir: 'icons/info-circle' } : null
        });
        vwWrapp.id = viewerOptsState.id;
        vwWrappBody.classList.add('cl-btn-viewer__body');
        vwWrapp.classList.add('cl-btn-viewer');
        vwWrapp.appendChild(vwLbl);
        vwWrapp.appendChild(btnEle);
        vwWrapp.appendChild(vwWrappBody);
        // add tooltip to the elements which requires it 
        if (tooltipTxt) {
            tippy(vwLbl.querySelector('i'), {
                content: tooltipTxt,
                theme: 'clicklease',
                maxWidth: 180,
            });
        }
        if (innerEle) {
            vwWrappBody.appendChild(innerEle);
        }
        return vwWrapp;
    }

    const fetchBtn = async (btnId) => {
        return ClButtonStyle.createButton({
            type: "wrapp",
            value: btnId,
            price: 15000,
            token: document.querySelector('input[id=clk_token]').value,
            url: document.querySelector('#clk_op_redurl').value
        }).then(res => {
            return res;
        });
    }

    const build = async () => {
        // ButtonSyle() returns instance of ClButton
        const clBtnFetched = await fetchBtn(svgId);
        const newVw = createViewer(clBtnFetched);
        viewerOptsState.eles.push(newVw);
        changeElesTheme();
        // console.log(newVw);
        return newVw;
    }

    return { build }
}