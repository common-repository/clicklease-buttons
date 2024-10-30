const ElementsViewer = function (viewrOptsArr) {
    let crrState = {
        viewersOptsList: viewrOptsArr,
        fldsStyles: {
            getTheme: (vwType) => document.querySelector(`#clk_op_theme_${vwType}`),
            getDarkMode: (vwType) => document.querySelector(`#clk_op_dark_mode_${vwType}`)
        }
    };

    const setState = (newState) => {
        crrState = {
            ...crrState,
            ...newState
        }
        // updateElement();
    }
    const getBtnViewersElePromise = (pViewrOptsArr) => {
        let arrResult = [];
        if (pViewrOptsArr instanceof Array) {
            //LOOP
            arrResult = pViewrOptsArr.map(opts => {
                const { id, btnType, lbl, elesOpts, btnsList, btnStyle, tooltipTxt = null, value } = opts;
                // console.log('%cFetched:', 'background: red;', btnsList);
                opts.instance = ClButtonsViewer(id, btnType,
                    {
                        lbl: lbl,
                        simpleValue: value,
                        tooltipTxt: tooltipTxt,
                        elesOpts: elesOpts,
                        btnsList: btnsList,
                        btnStyle: btnStyle,
                        flds: {
                            theme: crrState.fldsStyles.getTheme(btnStyle).value,
                            darkMode: crrState.fldsStyles.getDarkMode(btnStyle).value
                        }
                    }
                );
                // console.log(elesOpts);
                return opts.instance.build();
            })
        }
        return arrResult;
    }

    const build = async () => {
        const vwsWrapper = document.createElement('div');
        vwsWrapper.classList.add('elesViewerWrapp');
        // console.log(crrState.viewersOptsList);
        let arrViewers = [];
        try {
            arrViewers = await Promise.all(getBtnViewersElePromise(crrState.viewersOptsList))
        } catch (error) {
            console.log(error);
        }
        arrViewers.forEach((vwEle, i) => {
            vwsWrapper.appendChild(vwEle);
            // attachInput(vwEle, i);
        });

        return vwsWrapper;
    }

    const swithTheme = (vwEleId, newTheme, newIsDarkMode) => {

    }

    return { build, swithTheme };
}