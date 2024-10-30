const Backdrop = (bodyEle, id = `cl-backdrop-${new Date().valueOf()}`) => {

    const toggle = () => {
        console.log(id, crrEle);
    }

    const build = () => {
        const divEle = document.createElement('div');
        divEle.classList.add('backdrop');
        divEle.id = id;
        divEle.appendChild(bodyEle);
        return divEle;
    }

    return {
        build,
        toggle
    }
}