class ModuleAttributes {
    /**
     * @type {number}
     */
    moduleId = 0;

    /**
     * @param {number} moduleId
     */
    constructor(moduleId) {
        this.moduleId = moduleId;
        this.init();
    }

    init() {
        const module = document.getElementById("module-" + this.moduleId);

        if (module) {
            const link = module.querySelector(".activityname > a");
            link?.setAttribute('target', '_blank');
        }
    }
}

export const init = (moduleId) => {
    return new ModuleAttributes(moduleId);
};
