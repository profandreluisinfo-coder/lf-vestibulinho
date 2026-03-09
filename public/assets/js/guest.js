import { showToasts } from './ui/alerts/toasts.js';

import { initPasswordStrength } from './ui/auth/passwordStrength.js';
import { initRegisterValidation } from './forms/register/create.js';

showToasts();

initPasswordStrength();
initRegisterValidation();