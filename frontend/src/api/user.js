import axios from 'axios';

export const getUsers = (params = {}) => {
    return axios.get(`${process.env.REACT_APP_API_SERVER_PATH}/users`, {
            params
        } , {
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            const {data} = res
            const usersData = []
            if (data) {
                const users = data["hydra:member"]
                
                if (users && users.length) {
                    users.forEach(user => {
                        usersData.push({
                            'id': user.id,
                            'name': user.name,
                            'displayName': user.displayName,
                            'fullName': user.fullName,
                            'email': user.email
                        })
                    });
                }
            }
            return usersData
        })
}