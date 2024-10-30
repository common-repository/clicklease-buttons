const shoppingCartCustomPos = [
    {
        posId: 'pos_4',
        placeBtn: (btnEle) => {
            const wrappEle = btnEle.parentElement;
            wrappEle.insertBefore(btnEle, wrappEle.firstElementChild);
        }
    },
    {
        posId: 'pos_5',
        placeBtn: (btnEle) => {
            const wrappEle = btnEle.parentElement;
            btnEle.classList.add('btnCl--right');
            wrappEle.insertBefore(btnEle, wrappEle.firstElementChild);
        }
    },
];

const registerRenderSvg = async () => {
    const btnRefs = [...document.querySelectorAll('div[id*=clicklease-button]')];
    return Promise.all(
        btnRefs.map(async (btnRefEle, i) => {
            const btnType = btnRefEle.getAttribute('cl-btn-type');
            const leasingAmount = btnRefEle.getAttribute('cl-data-leasing');
            const isMobAlways = btnRefEle.getAttribute('cl-mobile-always');
            const redirectLink = btnRefEle.getAttribute('cl-redirect-url');
            const maxAmount = 25000;
            // console.log('btnRefEle', redirectLink);
            // console.log(btnType);
            return ClButtonStyle.createButton({ type: 'link', value: btnType, price: leasingAmount, url: redirectLink, pMaxAMount: maxAmount })
                .then(newClBtn => {
                    newClBtn.id = 'clk-button';
                    if (btnRefEle.id.includes('mobile')) {
                        newClBtn.classList.add('clLink--mobile');
                        if (isMobAlways) newClBtn.classList.add('clLink--mobile-always');
                    } else {
                        newClBtn.classList.add('clLink--desktop');
                    }
                    btnRefEle.insertAdjacentElement('afterend', newClBtn);
                    // btnRefEle.remove();
                    return newClBtn;
                })
        })
    ).then(res => {
        registerBtnCustomPositioning(res);
    });
}

const registerBtnCustomPositioning = (btnsArr) => {
    const btnRefs = [...document.querySelectorAll('div[id*=clicklease-button]')];
    btnRefs.forEach(clTagOpts => {
        let btnCustomPos = shoppingCartCustomPos.filter(({ posId }) => posId === clTagOpts.getAttribute('cl-cart-pos'));
        if (btnCustomPos.length) {
            btnCustomPos = btnCustomPos.pop();
            const btnFound = [...document.querySelectorAll('.clLink')]
                .filter(btnEle => btnEle.className.includes(clTagOpts.getAttribute('cl-vw-mode')));
            btnCustomPos.placeBtn(btnFound.pop());
            // console.log([...document.querySelectorAll(`#clk-button`)]);
        }
    });
    setTimeout(() => btnsArr.forEach(bEle => bEle.classList.add('clLink--show')), 1500);
}

/**
 * render buttons for shopping cart
 */
const registerRenderClBtnCart = function () {
    // create an observer that when it gets triggered check if the blueprint ele that 
    // are going to be replace with real buttons, is present. If it fetchs real buttons and then 
    // observer stops watching. Once btns were rendered start observing again  
    // and reomve the previous blueprints
    const shppCartObserver = new MutationObserver((mtArr, ob) => {
        for (const mt of mtArr) {
            // console.log(mt, ob);
            if (document.querySelectorAll('div[id*=clicklease-button]').length) {
                registerRenderSvg()
                    .then(res => {
                        [...document.querySelectorAll('div[id*=clicklease-button]')].forEach(b => b.remove());
                        ob.observe(document.body,
                            { attributes: false, childList: true, subtree: true });
                    });
                ob.disconnect();
                return;
            }
        }
    });
    shppCartObserver.observe(document.body,
        { attributes: false, childList: true, subtree: true });
    console.log('cart observer');
}

// const registerIframeFunctionality = function () {
//     setTimeout(() => {
//         clk_onClick();
//         function clk_onClick() {
//             document.getElementById("clk-button").addEventListener("click", openModal);
//         }
//     }, 500)

//     function openModal() {
//         modal = document.querySelector('.clk-modal');
//         modal.style = 'display: flex;'
//     }
// }

window.addEventListener('load', (e) => {
    // registerRenderSvg();
    // LOAD IFRAME
    // registerIframeFunctionality();
});