import { Component, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';

@Component({
    selector: 'app-login',
    standalone: false,
    templateUrl: './login.component.html',
    styleUrl: './login.component.css'
})
export class LoginComponent {
    username = signal('');
    password = signal('');
    error = signal('');

    constructor(private auth: AuthService, private router: Router) { }

    handleSubmit() {
        this.auth.login({ username: this.username(), password: this.password() }).subscribe({
            next: (response: any) => {
                if (response.success) {
                    this.router.navigate(['/dashboard']);
                }
            },
            error: (err) => {
                this.error.set(err.error?.message || 'Login failed. Network error?');
            }
        });
    }
}
