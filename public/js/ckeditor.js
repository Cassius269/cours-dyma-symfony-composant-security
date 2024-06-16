// On crée CK Editor sur la div ayant l'attribut #editor
BalloonEditor. create( document.querySelector( '#editor' ), {
  //  toolbar:['heading','|','bold','italic','|','bulletedList','link'],
    heading:{
        options:[
            {
                model:'paragraph',
                title: 'Paragraphe',
                class: 'ck-editor_paragraphe'
            },
            {
                model:'heading1',
                title: 'Gros titre',
                view: {
                    name: 'h1',
                    classes: 'ck-editor_heading1'
                },
            },
            {
                model:'heading2',
                title: 'Sous-titre',
                view: {
                    name: 'h2',
                    classes: 'ck-editor_heading2'
                }
            },
            
        ]
    },
    cloudServices: {
        tokenUrl: 'https://110564.cke-cs.com/token/dev/jjMCuDGURbNcVjNAS1ZXzZpawhe0IvLCXeX5?limit=10 ',
        uploadUrl: 'https://110564.cke-cs.com/easyimage/upload/'
    }
} )
.then(editor => {
    let inputHiddenContent = document.querySelector("#post_content");
    let form = document.querySelector("form");

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        inputHiddenContent.value = editor.getData();
        form.submit();
    });
})
.catch(error => {
console.error(error);
});

