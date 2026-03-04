import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthService } from '../auth.service';

@Component({
    selector: 'app-dashboard',
    standalone: true,
    imports: [CommonModule],
    templateUrl: './dashboard.component.html'
})
export class DashboardComponent {
    private auth = inject(AuthService);
    user = this.auth.currentUser;

    handleLogout() {
        this.auth.logout();
    }
}
