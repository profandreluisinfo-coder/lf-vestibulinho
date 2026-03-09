import { showToasts } from './ui/alerts/toasts.js';
import { initChangePasswordValidation } from './forms/password/change-password.js';
import { initPasswordStrength } from './ui/auth/passwordStrength.js';

showToasts();
initPasswordStrength();
initChangePasswordValidation();