import { Injectable, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable, tap } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class AuthService {
    private apiUrl = 'http://localhost:8080/api'; // Adjust base URL as needed
    isAuthenticated = signal(false);
    currentUser = signal<any>(null);

    constructor(private http: HttpClient, private router: Router) {
        const token = localStorage.getItem('token');
        const user = localStorage.getItem('user');
        if (token && user) {
            this.isAuthenticated.set(true);
            this.currentUser.set(JSON.parse(user));
        }
    }

    login(credentials: any): Observable<any> {
        return this.http.post(`${this.apiUrl}/auth/login.php`, credentials).pipe(
            tap((response: any) => {
                if (response.success) {
                    localStorage.setItem('token', response.token);
                    localStorage.setItem('user', JSON.stringify(response.user));
                    this.isAuthenticated.set(true);
                    this.currentUser.set(response.user);
                }
            })
        );
    }

    logout() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        this.isAuthenticated.set(false);
        this.currentUser.set(null);
        this.router.navigate(['/login']);
    }
}
