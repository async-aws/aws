exports.handler = async (event) => {
    function wait(event){
        return new Promise((resolve, reject) => {
            setTimeout(() => resolve(`hello ${event.name}`), event.delay)
        });
    }

    if (typeof(event.delay) === "undefined" || event.delay < 1) {
        event.delay = 1;
    }

    return wait(event);
};
