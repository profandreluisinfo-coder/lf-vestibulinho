import { showToasts } from './ui/toasts.js';
import { initChangePasswordValidation } from './forms/password/change-password.js';
import { initPasswordStrength } from './pass/strength.js';

showToasts();
initPasswordStrength();
initChangePasswordValidation();