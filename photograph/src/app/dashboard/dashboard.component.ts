import { Component, inject } from '@angular/core';
import { AuthService } from '../auth.service';

type TagSeverity = 'info' | 'success' | 'danger' | 'secondary' | 'warn' | 'contrast';

@Component({
    selector: 'app-dashboard',
    standalone: false,
    templateUrl: './dashboard.component.html',
    styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent {
    private auth = inject(AuthService);
    user = this.auth.currentUser;

    stats: { label: string; value: number; severity: TagSeverity }[] = [
        { label: 'Today\'s Shoots', value: 3, severity: 'info' },
        { label: 'Editing Queue', value: 5, severity: 'warn' },
        { label: 'Galleries To Deliver', value: 2, severity: 'success' },
        { label: 'Unpaid Invoices', value: 1, severity: 'danger' }
    ];

    upcomingShoots = [
        { client: 'John & Emily', date: 'Today 4:00 PM', location: 'Central Park', type: 'Engagement', status: 'Confirmed' },
        { client: 'Acme Corp', date: 'Tomorrow 11:00 AM', location: 'Studio', type: 'Headshots', status: 'Planning' },
        { client: 'The Smiths', date: 'Sat 2:00 PM', location: 'Beach', type: 'Family', status: 'Confirmed' }
    ];

    highlightImages = [
        { itemImageSrc: 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg', thumbnailImageSrc: 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg?w=200', alt: 'Wedding couple', title: 'Wedding Stories' },
        { itemImageSrc: 'https://images.pexels.com/photos/1024311/pexels-photo-1024311.jpeg', thumbnailImageSrc: 'https://images.pexels.com/photos/1024311/pexels-photo-1024311.jpeg?w=200', alt: 'Portrait', title: 'Studio Portraits' },
        { itemImageSrc: 'https://images.pexels.com/photos/2486168/pexels-photo-2486168.jpeg', thumbnailImageSrc: 'https://images.pexels.com/photos/2486168/pexels-photo-2486168.jpeg?w=200', alt: 'Family', title: 'Family Session' }
    ];

    handleLogout() {
        this.auth.logout();
    }
}
