console.log('Payments Logos loaded.....');

var observer = new MutationObserver(function(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        //console.log('Tipo de mutación:', mutation.type);
        // Verifica si se añadieron nuevos nodos
        if (mutation.type === 'childList') {
            //console.log('Nuevos nodos añadidos:', mutation.addedNodes);
            mutation.addedNodes.forEach(function(node) {

                try{

                    // Decodificar la cadena de consulta de la URL
                    var decodedURL = decodeURIComponent(window.location.search);

                    if (decodedURL.includes('?accounts=*&wallet=*&deposit=*') || decodedURL.includes('?accounts=*&wallet=*&deposit-methods=*')){
                        console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A Loaded.');
                    } else {
                        console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A Not Loaded.');
                    }
                


                } catch (error) {
                    console.error('Error: ', error);
                }

            });
        }
    });
});

// Observa los cambios en el cuerpo del documento y en sus descendientes
console.log('Watching changes on DOM...');
observer.observe(document.body, { childList: true, subtree: true });