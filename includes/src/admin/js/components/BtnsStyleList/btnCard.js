const BtnCard = function (tlt, value, action, pExtraOpts, contentProjected = null) {

    let props = {
        instance: null,
        extraOpts: {
            searchRes: true,
            ...pExtraOpts
        }
    }


    const createEle = async () => {
        const item = document.createElement('li');
        const cardTltEle = document.createElement('span');
        item.classList.add('cl-btn-card-item');
        cardTltEle.textContent = tlt;
        cardTltEle.classList.add('cl-btn-card-item__tlt');
        // content to be projected on card
        if (contentProjected) {
            item.appendChild(contentProjected || document.createElement('span'));
        } else {
            const creator = ClButton(value, 250, { ...props.extraOpts });
            props.instance = creator;
            const newBtn = await (value && creator.build()) || null;
            newBtn.appendChild(cardTltEle)
            item.appendChild(newBtn);
        }
        // contentProjected.setAttribute('value', value);
        // 
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // console.log(item.querySelector('svg').getBBox().width);
                resolve(item);
            }, 10);
        });
    }

    const build = async () => {
        const newCard = await createEle();
        if (action) newCard.onclick = (e) => action({
            listWrapp: newCard.parentElement.parentElement,
            value: value,
            e: e
        });
        return newCard;
    }

    return {
        build
    };
}