// Utilitários para validação
export const validateEmail = (email: string): boolean => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

export const validatePassword = (password: string): { isValid: boolean; errors: string[] } => {
  const errors: string[] = [];
  
  if (password.length < 6) {
    errors.push('A senha deve ter pelo menos 6 caracteres');
  }
  
  return {
    isValid: errors.length === 0,
    errors
  };
};

export const validatePrice = (price: number): boolean => {
  return price >= 0 && Number.isFinite(price);
};

export const validateRequired = (value: string): boolean => {
  return value.trim().length > 0;
};

export const validateMaxLength = (value: string, maxLength: number): boolean => {
  return value.length <= maxLength;
};

export const validateForm = (
  fields: Record<string, any>,
  rules: Record<string, Array<(value: any) => string | null>>
): { isValid: boolean; errors: Record<string, string[]> } => {
  const errors: Record<string, string[]> = {};
  
  for (const [fieldName, fieldRules] of Object.entries(rules)) {
    const fieldValue = fields[fieldName];
    const fieldErrors: string[] = [];
    
    for (const rule of fieldRules) {
      const error = rule(fieldValue);
      if (error) {
        fieldErrors.push(error);
      }
    }
    
    if (fieldErrors.length > 0) {
      errors[fieldName] = fieldErrors;
    }
  }
  
  return {
    isValid: Object.keys(errors).length === 0,
    errors
  };
};
