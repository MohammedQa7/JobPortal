
export function closeDialog(emit) {
    const dialogContent = document.querySelectorAll('.radix-dialog-content');
    dialogContent.forEach((node) => {
        node.setAttribute('data-state', 'closed');
    })
    setTimeout(() => {
        emit('Close', false);
    }, 130);
}