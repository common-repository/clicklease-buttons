const Backdrop = (bodyEle) => {

    const build = () => {
        const divEle = document.createElement('div');
        divEle.classList.add('backdrop');
        divEle.appendChild(bodyEle);
        return divEle;
    }

    return {
        build
    }   
}