/**
 * 
 * @param {object} Object Propiedades: 
 * msg: Mensaje que mostará el aviso. 
 * type: Formato del aviso ('success', 'error', 'warning', 'info', 'question').
 */
const showNotif = ({msg, type})=>{
    const types = [
        'success',
        'error',
        'warning',
        'info',
        'question',
    ];

    if (msg?.length && types.includes(type))
        Swal.fire({
            position: 'top-end',
            icon: type,
            title: msg,
            showConfirmButton: false,
            timer: 1500
        });
}




/**
 * Procesa la respuesta para mostar en la página los resultados.
 * @param {object} objResult Objeto con el resultado de la petición al servidor.
 */
const submitFormProcessResult = (card, objResp)=>{
    const   titulo      = card.querySelector('.card-title'),
            subtitulo   = card.querySelector('.card-subtitle'),
            boton       = card.querySelector('#btnDeleteIp');

    const   {ok, data:{ip, country}} = objResp;

    if (ok && ip && country){
        titulo.innerHTML = ip;
        subtitulo.innerHTML = country;
        boton.dataset.ip = ip;
    }        
}




/**
 * Envío de la consulta de búsqueda de IP al servidor.
 * @param {object} data Sus propiedades serán enviadas como parámetros al servidor.
 * @param {Element} card Elemento HTML con el bloque donde se presenta la información de la IP.
 */
const submitForm = async (data, card)=>{
    await $.ajax({
        url: 'controller/getBusqueda',
        method: 'post',
        data,
        beforeSend:()=>{
            $(card).toggleClass('d-none', true);
        },
        error:(error)=>{
            console.error(error);
            showNotif({type:'error', msg: 'Error al enviar la solicitud'});
        },
        success:(result)=>{
            try {
                const objResp = JSON.parse(result);
                console.log(objResp);
                submitFormProcessResult(card, objResp);
                $(card).toggleClass('d-none', false);
            } 
            catch (error) {
                console.error('ERROR. No ha sido posible procesar la respuesta del servidor');
                console.error(error);
                showNotif({type:'error', msg: 'Error inesperado al enviar la solicitud'});
            }
        }
    });
}




/**
 * Envío de la consulta de eliminación de IP al servidor.
 * La IP la obtiene de uno de los atributos data del botón que lanzó el evento.
 * @param {Event} e Evento del botón que ha lanzado esta función.
 * @param {Element} card Elemento HTML con el bloque donde se presenta la información de la IP.
 */
const removeIp = (e, card)=>{
    $.ajax({
        url: 'controller/removeIp',
        method: 'delete',
        data:{
            ip: e.target.dataset.ip,
        },
        beforeSend:()=>{
            $(card).toggleClass('d-none', true);
        },
        error:(error)=>{
            console.error(error);
            showNotif({type:'error', msg: 'Error al intentar eliminar la IP'});
            $(card).toggleClass('d-none', false);
        },
        success:(result)=>{
            try {
                const objResp = JSON.parse(result);

                if (objResp?.ok === true)
                    showNotif({type:'info', msg: 'IP eliminada de la base de datos'});
                else{
                    showNotif({type:'error', msg: objResp.msg ?? 'No ha sido posible eliminar la IP'});
                    $(card).toggleClass('d-none', false);
                }
            } catch (error) {
                console.error(error);
                showNotif({type:'error', msg: 'Error inesperado al intentar eliminar la IP'});
            }
        }
    });
}









document.addEventListener('DOMContentLoaded', (e)=>{
    /** Establece el título de la página y del navbar. */
    const title = 'PRUEBA TÉCNICA';
    document.title = title;
    document.querySelector('header .navbar-title').innerHTML = title;

    /** Elemento HTML con el bloque donde se presenta la información de la IP.  */
    const card = document.querySelector('#cardIp');


    /** Botón de eliminar el registro de la IP. */
    document.querySelector('#btnDeleteIp').addEventListener('click', e => removeIp(e, card))


    /** Envío de la consulta de cual es mi IP. */
    document.querySelector('#linkMyIp').addEventListener('click', ()=> submitForm({myIp:''}, card));


    /** Manejo del submit del formulario de búsqueda. */
    document.querySelector('#formSearch').addEventListener('submit', e =>{
        e.preventDefault();
        const formData = new FormData(e.target);
                
        submitForm({search: formData.get('search')}, card);
    });
});