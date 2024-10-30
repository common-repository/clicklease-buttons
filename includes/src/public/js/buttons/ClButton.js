const BUTTON_TYPE = {
    getList: function (type = BUTTON_TYPE.NORMAL) {
        return Object.entries(type).map(([k, v]) => v)
    },
    NO_LEASING: {
        btn_1: { value: 'btn_1', label: '' },
        btn_2: { value: 'btn_2', label: '' },
        btn_5: { value: 'btn_5', label: '' },
        // btn_6: { value: 'btn_6', label: '' },
        // btn_7: { value: 'btn_7', label: '' }
    },
    MOBILE: {
        btn_1: { value: '/mobile/btn_1', label: '' },
        btn_2: { value: '/mobile/btn_2', label: '' },
        btn_3: { value: '/mobile/btn_3', label: '' },
        btn_4: { value: '/mobile/btn_4', label: '' },
        btn_5: { value: '/mobile/btn_5', label: '' },
        // btn_6: { value: 'btn_6', label: '' },
        // btn_7: { value: 'btn_7', label: '' }
    },
    NORMAL: {
        btn_1: { value: 'btn_1', label: '' },
        btn_2: { value: 'btn_2', label: '' },
        btn_3: { value: 'btn_3', label: '' },
        // btn_4: { value: 'btn_4', label: '' },
        btn_5: { value: 'btn_5', label: '' },
        btn_8: { value: 'btn_8', label: '' },
    }
};

const ClButton = function (svgId, leaseVal, stylesOpts = {}) {

    let btnState = {
        svgId: svgId,
        leaseVal: leaseVal,
        styleOpts: {
            eleType: 'btn', // link(a), btn(button) or wrapp(div)
            link: '', // only taken in consideration for link eles
            ...stylesOpts
        }
    };
    let crrClBtn = null;

    let setBtnState = async (newBtnState) => {
        // if all attrs are not passed it set default ones
        const newStyleOpts = { ...btnState.styleOpts, ...(newBtnState.styleOpts || {}) }
        btnState = { ...btnState, ...newBtnState, styleOpts: newStyleOpts };
        await setSvgId(btnState.svgId);
        setLeaseVal(btnState.leaseVal);
        // After this should trigeer something that indicates that it changes
        // Maybe with an insteance external code can apply changes through this method and
        // subscribe to state changes
    }

    const getBtnState = () => ({ ...btnState });

    const getSvgContent = (objectEle) => {
        let svgCont = objectEle.contentDocument.documentElement.cloneNode(true);
        console.log(svgCont);
        let svgWrapp = null;
        if (svgCont) {
            svgWrapp = objectEle.parentElement;
            objectEle.remove();
            svgWrapp.appendChild(svgCont);
        }
        return svgWrapp;
    }

    const getWrappType = (eleType) => {
        let newClWrapp;
        switch (eleType) {
            case 'link':
                newClWrapp = document.createElement('a');
                newClWrapp.setAttribute('target', '_blank');
                newClWrapp.classList.add('clLink', 'clEle');
                newClWrapp.href = btnState.styleOpts.link;
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
        return newClWrapp;
    }

    const createSvgEle = (newSvgId) => {
        let svgWrapp = getWrappType(btnState.styleOpts.eleType);
        // svgWrapp.style = 'min-width: 520px; max-width:600px;'; // REMOVE THIS
        return fetch(findSvg(newSvgId))
            .then(res => {
                return res.text();
            })
            .then(svg => {
                // console.log(svg);
                if (svg.toLocaleLowerCase().includes('<svg xmlns')) {
                    // return svg;
                    svgWrapp.innerHTML = svg;
                    // it requires svg to be attahced in order to move lease amount dynamically
                    if (svgWrapp.querySelector('svg')) {
                        if (getCrrBtn()) getCrrBtn().remove();
                        crrClBtn = svgWrapp;
                        return svgWrapp;
                    }
                    // else ;
                }
                throw new Error('resource wanted not found')
            })
            .catch(err => {
                console.log(err.message);
                // throw new Error(err);
            });
    }

    const moveSVGEle = (svgEle, coor) => {
        const { x, y } = coor;
        const svgRoot = svgEle.viewportElement;
        const svgTrans = svgRoot.createSVGTransform();
        svgTrans.setTranslate(x, y);
        svgEle.transform.baseVal.appendItem(svgTrans);
    }

    const positionLeaseVal = (svgEle) => {
        const elesMov = [...svgEle.querySelectorAll(`.move`)];
        const leaseTextEle = svgEle.querySelector(`.cl-btn-price`)?.getBBox();
        if (elesMov.length) {
            let pxMov;
            if (leaseTextEle) pxMov = leaseTextEle.width - 18; // 18 is  the average px width for number between 0-9
            elesMov.forEach(el => {
                moveSVGEle(el, { x: pxMov, y: 0 });
            });
            const reverse = [...svgEle.querySelectorAll(`.move--reverse`)];
            if (reverse.length) reverse.forEach(el => moveSVGEle(el, { x: -(pxMov / 2), y: 0 }));
        }
    }

    async function setSvgId(newSvgId) {
        btnState.svgId = newSvgId;
        // const newBtn = await createSvgEle(newSvgId);
        return;
    }

    const setLeaseVal = (newLeaseAmount) => {
        const priceEle = getSvg()?.querySelector('.cl-btn-price');
        if (priceEle) {
            // console.log(newLeaseAmount);
            priceEle.textContent = '$' + newLeaseAmount;
            positionLeaseVal(getSvg());
        }
    }

    const getSvg = () => {
        if (!crrClBtn) return null;
        return crrClBtn.firstElementChild;
    }

    const getCrrBtn = () => crrClBtn;


    const updateElement = (newSvgId) => {
        const svgWrapper = getCrrBtn();
        const loadingEle = document.createElement('p');
        loadingEle.textContent = 'loading...';
        loadingEle.classList.add('cl-btn-loading', 'cl-replace_' + newSvgId);
        svgWrapper.parentElement.insertBefore(loadingEle, svgWrapper);
        btnState.svgId = newSvgId;
        console.log(newSvgId);
        //If the correct svg is not found will fail
        createSvgEle(newSvgId)
            .then(resNewBtn => {
                // console.log(resNewBtn);
                setLeaseVal(btnState.leaseVal);
                loadingEle.parentElement.insertBefore(resNewBtn, loadingEle);
                [...getCrrBtn().parentElement.querySelectorAll('.cl-btn-loading')].forEach(ele => ele.remove());
            })
            .catch(err => {
                // console.log(err);
                [...getCrrBtn().parentElement.querySelectorAll('.cl-btn-loading')].forEach(ele => ele.remove());
            });
    }

    const build = async () => {
        // Here customize some way svg gotten and is return asynchronous
        const baseBtnEle = await createSvgEle(btnState.svgId);
        await setBtnState({
            'svgId': svgId,
            'leaseVal': leaseVal,
        });
        return crrClBtn;
    }

    return { build, updateElement };
}
