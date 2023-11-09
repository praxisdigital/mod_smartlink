class ModuleAttributes {
    constructor(moduleid) {
        this.moduleid = moduleid;
        this.init();
    }

    init() {
        const module = document.getElementById("module-" + this.moduleid);

        if (module) {
            const link = module.querySelector(".activityname > a");
            link?.setAttribute('target', '_blank');
        }
    }
}

export const init = (moduleid) => {
    return new ModuleAttributes(moduleid);
};
