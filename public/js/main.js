/************************** script pour étendre le click a toute la card ****/

const articleCard = document.querySelectorAll('.article-grid')

articleCard.forEach(card=> {
    card.addEventListener('click',()=>{
        card.querySelector('a').click()
    })

})


