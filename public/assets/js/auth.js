import { showToasts } from './ui/alerts/toasts.js';

import { initPasswordStrength } from './ui/auth/passwordStrength.js';
import { initLoginValidation } from './forms/auth/login.js';
import { initRegisterValidation } from './forms/register/create.js';
import { initResendEmailValidation } from './forms/register/resend-email.js';
import { initResetPasswordValidation } from './forms/password/reset-password.js';
import { initForgotPasswordValidation } from './forms/password/forgot-password.js';
import { initChangePasswordValidation } from './forms/password/change-password.js';

showToasts();

initPasswordStrength();
initLoginValidation();
initRegisterValidation();
initResendEmailValidation();
initResetPasswordValidation();
initForgotPasswordValidation();
initChangePasswordValidation();