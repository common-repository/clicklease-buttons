const ClModal = function (id, tlt, desc, body, actns) {


    const toggle = () => {
        document.querySelector(`#${id}`).classList.toggle('cl-modal--hide');
        document.querySelector(`#${id}`).parentElement.classList.toggle('backdrop--hide');
    }

    const createButton = (id, text, styleType, action) => {
        return ClAppButton(
            id,
            [{
                type: 'click', cb: action
            }],
            {
                btnType: 'button',
                lbl: text,
                styleType: styleType,
            }).build();
    }

    const build = () => {
        const wrappEle = document.createElement('div');
        wrappEle.classList.add('cl-modal');
        wrappEle.setAttribute('id', String(id));
        wrappEle.innerHTML = `
        <div class="cl-modal__header">
            <h1>${tlt}</h1>
            <p>${desc}</p>
        </div>
        <div class="cl-modal__body">
        </div>
        <div class="cl-modal__footer"></div>`;
        if (typeof body === 'string') wrappEle.querySelector('.cl-modal__body').innerHTML = body;
        else wrappEle.querySelector('.cl-modal__body').appendChild(body);
        wrappEle.classList.add('cl-modal--hide');
        // append buttons
        actns instanceof Array && actns.forEach((btnOpts, i) => {
            const { lbl, style, onclick } = btnOpts;
            wrappEle.querySelector('.cl-modal__footer')
                .appendChild(createButton('mdl-btn-' + i, lbl, style, onclick));
        });
        return Backdrop(wrappEle).build();
    }

    return {
        build,
        toggle
    }
}