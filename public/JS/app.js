class Filter {
    /**
     * 
     * @param {HTMLElement|null} element 
     */
    constructor (element) {
        if (element === null) {
            return
        }
        this.sorting = element.querySelector('.js-filter-sorting')
        this.content = element.querySelector('.js-filter-content')
        this.events()
    }

    events () {
        this.sorting.addEventListener('click', e => {
            if(e.target.tagName === 'A') {
                e.preventDefault()
                this.loadUrl(e.target.getAttribute('href'))
            }
        })
    }

    async loadUrl(url) {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        //on test sur la requete a reussi
        if(response.status >= 200 && response.status < 300) {
            const data = await response.json()
            this.content.innerHTML = data.content
            this.sorting.innerHTML = data.sorting
        } else {
            console.error(response)
        }
    }
}

new Filter(document.querySelector('.js-filter'))