import { showToasts } from '/assets/js/ui/toasts.js';
import { initChangePasswordValidation } from '/assets/js/forms/password/change-password.js';
import { initPasswordStrength } from '/assets/js/pass/strength.js';

showToasts();
initPasswordStrength();
initChangePasswordValidation();