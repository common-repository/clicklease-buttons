class ClSpinner {

    #id;
    #props

    #eleBuild;

    constructor(id, props) {
        this.#id = id;
        this.#props = props;
    }

    createElement() {
        const loaderWrapp = document.createElement('div');
        const loaderEle = document.createElement('div');
        if (!this.#props.loaderType) loaderEle.classList.add('cl-dots-loader');
        else loaderEle.classList.add(`cl-${this.#props.loaderType.toLowerCase()}-loader`);
        if (this.#props.iconUrl) {
            loaderWrapp.style = `--cl-spn-icon: url(${this.#props.iconUrl});`;
            loaderWrapp.classList.add('cl-loader-wrapp--icon');
        }
        loaderWrapp.id = this.#id;
        loaderWrapp.classList.add('cl-loader-wrapp');
        loaderWrapp.appendChild(loaderEle);
        return loaderWrapp;
    }

    remove() {
        if (this.getEleBuild()) {
            const msFadeAnim = 180;
            this.getEleBuild().style = `opacity: 0; --cl-spn-fade-anim: ${msFadeAnim}ms;`;
            setTimeout(() => this.getEleBuild().remove(), msFadeAnim + 30)
        }
    }

    build() {
        this.setEleBuild(this.createElement());
        return this.getEleBuild();
    }

    setEleBuild(pEleBuild) {
        this.#eleBuild = pEleBuild;
    }
    getEleBuild(pEleBuild) {
        return this.#eleBuild;
    }
}