import { showToasts } from './ui/toasts.js';

import { initLoginValidation } from './forms/auth/login.js';
import { initRegisterValidation } from './forms/register/create.js';
import { initResendEmailValidation } from './forms/register/resend-email.js';
import { initResetPasswordValidation } from './forms/password/reset-password.js';
import { initForgotPasswordValidation } from './forms/password/forgot-password.js';
import { initChangePasswordValidation } from './forms/password/change-password.js';

// Edital
import { confirmNoticeDelete } from './swa/notice/delete.js'; // Importação da função de confirmação de exclusão de edital
import { confirmNoticePublish } from './swa/notice/publish.js'; // Importação da função de confirmação de publicação de edital

// Cursos
import { confirmCourseDelete } from './swa/courses/delete.js'; // Importação da função de confirmação de exclusão de curso

window.confirmCourseDelete = confirmCourseDelete; // Tornar a função de confirmação de exclusão de curso acessível globalmente

window.confirmNoticeDelete = confirmNoticeDelete; // Tornar a função de confirmação de exclusão de edital acessível globalmente
window.confirmNoticePublish = confirmNoticePublish; // Tornar a função de confirmação de publicação de edital acessível globalmente

showToasts();

initLoginValidation();
initRegisterValidation();
initResendEmailValidation();
initResetPasswordValidation();
initForgotPasswordValidation();
initChangePasswordValidation();