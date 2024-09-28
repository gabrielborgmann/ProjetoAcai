document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('acai-form');
    const cartItems = document.getElementById('cart-items');

    const addToCartButton = document.getElementById('add-to-cart-btn');

    addToCartButton.addEventListener('click', () => {
        const size = document.getElementById('size').value;
        const selectedFruits = Array.from(document.querySelectorAll('input[name="fruits"]:checked')).map(input => input.value);
        const selectedToppings = Array.from(document.querySelectorAll('input[name="toppings"]:checked')).map(input => input.value);

        if (selectedFruits.length === 0 && selectedToppings.length === 0) {
            alert('Selecione pelo menos uma fruta ou um topping.');
            return;
        }

        const cartItem = document.createElement('li');
        cartItem.innerHTML = `
            <span>${size} Açaí com ${selectedFruits.join(', ')} e ${selectedToppings.join(', ')}</span>
            <button class="edit-btn"><i class="fas fa-edit"></i></button>
            <button class="delete-btn"><i class="fas fa-trash"></i></button>
        `;

        cartItems.appendChild(cartItem);

        cartItem.querySelector('.edit-btn').addEventListener('click', () => {
            // Aqui você pode adicionar a lógica para editar o item, se necessário
            alert('Funcionalidade de edição ainda não implementada.');
        });

        cartItem.querySelector('.delete-btn').addEventListener('click', () => {
            cartItem.remove();
        });
    });
});
