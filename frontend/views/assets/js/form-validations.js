function validateEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailRegex.test(email);
}

function validatePhone(phone) {
    // Validar que sean exactamente 8 dígitos
    const phoneRegex = /^[0-9]{8}$/;
    return phoneRegex.test(phone);
}

function validatePassword(password) {
    console.log('[Validación] Verificando contraseña:', password);
    
    // Al menos 8 caracteres, una mayúscula, una minúscula y un número
    const minLength = password.length >= 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /\d/.test(password);
    
    console.log('[Validación] Requisitos de contraseña:');
    console.log('- Longitud mínima (8):', minLength);
    console.log('- Contiene mayúscula:', hasUpperCase);
    console.log('- Contiene minúscula:', hasLowerCase);
    console.log('- Contiene número:', hasNumber);
    
    const isValid = minLength && hasUpperCase && hasLowerCase && hasNumber;
    console.log('[Validación] Contraseña válida:', isValid);
    
    return isValid;
}

function validateName(name) {
    // Solo letras y espacios, mínimo 2 caracteres
    const nameRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/;
    return nameRegex.test(name);
}

function validateAddress(address) {
    // Mínimo 5 caracteres, letras, números y caracteres básicos
    return address.length >= 5;
}