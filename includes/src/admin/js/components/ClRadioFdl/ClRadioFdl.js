// @param type this could be radio or button
const ClRadioFdl = function (fldId, lblText, ratioOptsArr, onChange, type = 'radio', defValue) {

    let props;

    const init = () => {
        props = {
            realFdl: document.querySelector(`#${fldId}`),
            radiosName: fldId + '_fake',
            label: lblText,
            ratiosOpts: ratioOptsArr // []
        }
    }

    const createRadioEle = (type, opts) => {
        const { value, customStyle, name } = opts;
        let radioEle = document.createElement('input');
        radioEle.name = props.radiosName;
        radioEle.id = name;
        radioEle.type = 'radio';
        radioEle.classList.add('cl-ratio-ele');
        radioEle.value = value;
        radioEle.onclick = (e) => onChange(e);
        radioEle.onchange = ({ target }) => {
            props.realFdl.value = target.value;
            props.realFdl.setAttribute('value', target.value);
        }
        if (type === 'button') {
            const newFakeRadio = ClAppButton(
                name,
                [{
                    type: 'click', cb: (e) => {
                        const innerRadioEle = newFakeRadio.querySelector('input[type=radio]');
                        innerRadioEle.checked = true;
                        onChange(e);
                        innerRadioEle.onchange({ target: innerRadioEle });
                        // set active button radio
                        // console.log(innerRadioEle.parentElement.parentElement.querySelectorAll('.cl-ratio-ele'));
                        innerRadioEle.parentElement.parentElement.querySelectorAll('.cl-ratio-ele')
                            .forEach(ele => ele.classList.remove('cl-ratio-btn--active'))
                        e.target.classList.add('cl-ratio-btn--active');
                    }
                }],
                {
                    btnType: 'button',
                    lbl: '',
                    styleType: customStyle.customCssClasses,
                    customStyleSttgs: customStyle
                }).build();
            radioEle.classList.add('cl-fld--hidden');
            newFakeRadio.classList.add('cl-ratio-ele');
            // newFakeRadio.className += ` ${customStyle.customCssClasses}`;
            newFakeRadio.value = value;
            newFakeRadio.appendChild(radioEle);
            // if (customStyle) radioEle.className += ` ${customStyle}`;
            radioEle = newFakeRadio;
        }
        return radioEle;
    }

    const createElement = () => {
        const { label, ratiosOpts } = props;
        const fldWrapp = document.createElement('div');
        const globalLabel = document.createElement('label');
        if (label) globalLabel.textContent = label;
        globalLabel.classList.add('cl-radio-wrapp-lbl');
        fldWrapp.classList.add('cl-radios-wrapp')
        fldWrapp.id = props.radiosName + '-wrapp';
        fldWrapp.appendChild(globalLabel);
        ratiosOpts.forEach((op, i) => {
            op.name = props.radiosName + '-' + i;
            const radioEle = createRadioEle(type, op);
            let fldLabel;
            if (op.label) {
                fldLabel = document.createElement('label');
                fldLabel.classList.add('cl-radio-lbl');
                fldLabel.textContent = op.label;
                fldLabel.setAttribute('for', props.radiosName);
            }
            fldWrapp.appendChild(radioEle);
            fldLabel && fldWrapp.appendChild(fldLabel);
            if (!defValue) {
                if (i === 0) setActiveEle(radioEle)
            } else {
                if (defValue === op.value) setActiveEle(radioEle);
            }
        });
        return fldWrapp
    }

    const build = () => {
        const ratiosBoxEle = createElement();
        return ratiosBoxEle;
    }

    const setActiveEle = (radioEle) => {
        if (type !== 'radio') radioEle.classList.add('cl-ratio-btn--active');
        else radioEle.checked = true;
    }

    init();
    return {
        build
    }
}