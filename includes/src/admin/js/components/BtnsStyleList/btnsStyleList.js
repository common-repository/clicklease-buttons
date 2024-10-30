// @param cardsSize could be free, fixed, content
const BtnsStyleList = function ({ arrOpts, cardsSize = 'fixed', defaultValue = null, btnsList, onClick, searchRes = false }) {

    let activeValue = null;

    const markActive = ({ listWrapp, value, e }) => {
        let selectedBtn = e.target;
        [...listWrapp.querySelectorAll('.cl-btn-card-item')].forEach(li => {
            li.classList.remove('cl-btn-card-item--active');
        });
        selectedBtn.classList.add('cl-btn-card-item--active');
        activeValue = value;
    }

    const getActiveVal = () => {
        return activeValue;
    }

    const setCardsWidth = (wrappSvg, id, wType = 'disorder') => {
        const mediaBtnEle = wrappSvg.querySelector('img') || wrappSvg.querySelector('svg');
        if (mediaBtnEle) {
            let btnLenPer = wrappSvg.querySelector('svg')
                ? mediaBtnEle.getBBox().width / 10
                : 100 / 2.8;
            btnLenPer = Math.floor(btnLenPer);
            // document.body.appendChild(wrappSvg.querySelector('svg').cloneNode(true));
            if (btnLenPer > 80) btnLenPer = 78;
            if (btnLenPer < 20) btnLenPer = 24;
            wrappSvg.style.width = btnLenPer + '%';
            wrappSvg.classList.add('cl-btn-card');
            wrappSvg.id = `cl-btn-card-${id}`;
        }
    }

    const build = () => {
        const btnList = document.createElement('ul');
        btnList.classList.add('cl-btn-card-list');
        [...arrOpts]
            .forEach(({ label, value }) => {
                const clickAction = onClick
                    ? ((eValues) => { markActive(eValues); onClick(eValues) })
                    : markActive;
                const newCard = BtnCard(label, value, clickAction, { searchRes: searchRes });
                // newCard.setDarkMode(props.hasDarkMode);
                newCard.build()
                    .then(ele => {
                        btnList.appendChild(ele);
                        if (cardsSize === 'content') setCardsWidth(ele, value);
                        if (cardsSize.split('-')[0] === 'fixed') {
                            ele.style = `--cl-card-size: ${(100 / +cardsSize.split('-')[1] - 2) || 30}%;`;
                            ele.classList.add('cl-btn-card-item--fixed');
                        };
                        if (cardsSize === 'free') ele.classList.add('cl-btn-card-item--free');
                        // Set default active in different way
                        if (value === defaultValue) {
                            activeValue = value;
                            ele.classList.add('cl-btn-card-item--active')
                        };
                    });
            });
        return btnList;
    }

    const buildAsync = async () => {
        const btnListEle = document.createElement('ul');
        const spinnerLoader = new ClSpinner(`cl-btns-list-${Date.now()}`,
            { loaderType: 'dots', iconUrl: getURL() + 'cl-logo.svg' });
        btnListEle.style = '--cl-spn-fade-anim: 1;';
        if (btnsList) {
            btnListEle.classList.add('cl-btn-card-list', 'cl-btn-card-list--loading');
            btnListEle.appendChild(spinnerLoader.build());
            Promise.all(
                btnsList.map(async ({ label, value, content }) => {
                    const clickAction = onClick
                        ? ((eValues) => { markActive(eValues); onClick(eValues) })
                        : markActive;
                    const newCard = await BtnCard(label, value, clickAction, { searchRes: searchRes }, content)
                        .build();
                    btnListEle.appendChild(newCard)
                    setCardWithBehavior({ cardEle: newCard, value: value });
                    return newCard;
                })
            ).then(ulList => {
                // console.log(ulList);
                setTimeout(() => {
                    // ulList.forEach(card => btnListEle.appendChild(card));
                    spinnerLoader.remove();
                    btnListEle.style = '--cl-spn-fade-anim: 0;';
                    setTimeout(() => btnListEle.classList.remove('cl-btn-card-list--loading'), 200);
                }, 2500);
            });
        };
        return btnListEle;
    }

    const setCardWithBehavior = ({ cardEle, value }) => {
        if (cardsSize === 'content') setCardsWidth(cardEle, value);
        if (cardsSize.split('-')[0] === 'fixed') {
            cardEle.style = `--cl-card-size: ${(100 / +cardsSize.split('-')[1] - 2) || 30}%;`;
            cardEle.classList.add('cl-btn-card-item--fixed');
        };
        if (cardsSize === 'free') cardEle.classList.add('cl-btn-card-item--free');
        // Set default active in different way
        if (value === defaultValue) {
            activeValue = value;
            cardEle.classList.add('cl-btn-card-item--active')
        };
    }

    return {
        build, getActiveVal, buildAsync
    };
}