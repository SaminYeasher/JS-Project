document.addEventListener("DOMContentLoaded", () => {
    let observer = new MutationObserver((mutations) => {
        mutations.forEach((mutationRecord) => {
            let allLinksofSecondMenu= document.querySelectorAll("#mega-menu-primary> li:nth-child(3) ul a");
            setTimeout(() => {
                allLinksofSecondMenu.forEach((link) => {
                    if(link.hasAttribute("aria-expanded")) {
                        link.parentElement.classList.toggle("mega-toggle-on");
                        link.setAttribute("aria-expanded", "true");
                    }
                });
            }, mutationRecord.target.getAttribute("aria-expanded") === "true" ?0: 300); 
        });
    });    
    let secondMenuLink = document.querySelector("#mega-menu-primary> li:nth-child(3) > a");
    observer.observe (secondMenuLink, {
        attributes: true,
        attributeFilter: ["aria-expanded"],
        });
});