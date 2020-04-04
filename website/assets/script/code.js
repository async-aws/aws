for (const elem of document.querySelectorAll('code.hljs')) {
    const button = document.createElement('div');
    button.classList.add('btn-copy');
    button.title = 'copy to clipboard';
    button.addEventListener('click', () => {
        try {
            copyText(elem.innerText);
            button.title = 'copied!'
            button.classList.add('btn-copy-ok')
            setTimeout(() => {
                button.classList.remove('btn-copy-ok')
            }, 3000);
        } catch (e) {
            console.error(e);
            button.classList.add('btn-copy-error')
            setTimeout(() => {
                button.classList.remove('btn-copy-error')
            }, 3000);
        }
    });
    elem.prepend(button);
}

const copyText = text => {
    const t = document.createElement('textarea');
    try  {
        t.value = text;
        document.body.appendChild(t);
        t.select();
        t.setSelectionRange(0, 99999); /*For mobile devices*/
        if (!document.execCommand('copy')) {
            throw new Error('copy command was unsuccessful');
        }
    } finally {
        t.parentNode.removeChild(t);
    }
};
