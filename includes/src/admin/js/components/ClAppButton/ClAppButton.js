const ClAppButton = function (id, events, { lbl, icon, styleType = 'prim', btnType = 'button', customStyleSttgs }) {

    const palette = {
        'prim': { bg: '#1868c3', color: '#fff', outline: false },
        'sec': { bg: '#747474', color: '#fff', },
        'ter': { bg: '#00ADEE', color: '#fff' },
    }

    let props = {
        id: id,
        name: lbl,
        type: btnType,
        events: events || [],
        icon: {
            pos: 'left',
            value: null
        },
        styleType: (pStyleType = styleType) => {
            let cssVarsObj = palette['prim'];
            if (pStyleType.includes('custom')) {
                cssVarsObj = customStyleSttgs;
            } else {
                const kFound = Object.keys(palette).find(sType => sType.toLowerCase().includes(pStyleType.split('-')[0].toLowerCase()));
                cssVarsObj = kFound ? palette[kFound] : palette['prim'];
                if (btnType === 'button' && pStyleType.includes('outline')) cssVarsObj.outline = true;
            }
            // console.log(cssVarsObj);
            return cssVarsObj;
        },
        ele: null
    }

    const setIcon = (baseEle, value, side = 'left') => {
        const iEle = document.createElement('i');
        iEle.classList.add('cl-app-btn__icon');
        iEle.innerHTML = `
        <object type="image/svg+xml"
            data="${findSvg(value)}">
        </object>`;
        if (side === 'left') baseEle.appendChild(iEle);
        else {
            if (baseEle.firstElementChild) baseEle.insertBefore(iEle, baseEle.firstElementChild);
            else baseEle.appendChild(iEle);
        }
    }

    const setText = (baseEle, text) => {
        const textEle = document.createElement('span');
        textEle.textContent = text;
        textEle.classList.add('cl-app-btn__text');
        baseEle.appendChild(textEle);
    }

    const createElement = () => {
        let baseEle = props.type === 'a' ? 'a' : 'button';
        baseEle = document.createElement(baseEle);
        const { bg, color, outline = false } = props.styleType(styleType);
        baseEle.classList.add('cl-app-btn');
        if (outline) baseEle.classList.add('cl-app-btn--outline');
        if (props.type === 'button') {
            if (styleType.includes('-sm')) baseEle.classList.add('cl-app-btn-sm');
            if (styleType.includes('-lg')) baseEle.classList.add('cl-app-btn-lg');
        }
        baseEle.style = `--cl-plg-btn-bg: ${bg}; --cl-plg-btn-tx: ${color}; --cl-plg-clr-l: 35%;`;
        baseEle.id = props.id;
        baseEle.setAttribute('tabindex', '0');
        if (props.name) setText(baseEle, props.name);
        events.forEach(({ type, cb }) => {
            if (!baseEle['on' + type])
                baseEle['on' + type] = (e) => {
                    e.preventDefault();
                    cb(e);
                };
        });
        // adds an icon if present
        if (props.icon.value) setIcon(baseEle, props.icon.value, props.icon.pos);
        props.ele = baseEle;
        return baseEle;
    }
    const build = () => {
        return createElement();
    }

    // const setDarkMode = function(enable = false) {
    // }

    return { build }
}