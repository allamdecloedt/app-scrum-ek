import 'dart:convert';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:rive/rive.dart';

class SideMenu extends StatefulWidget {
  final Map<String, dynamic> userData;

  const SideMenu({Key? key, required this.userData}) : super(key: key);

  @override
  State<SideMenu> createState() => _SideMenuState();
}

class _SideMenuState extends State<SideMenu> {
  String? imageUrl;

  @override
  void initState() {
    super.initState();
    fetchUserImage();
  }

  Future<void> fetchUserImage() async {
    try {
      final userId = widget.userData['id'];
      final response = await http.get(
        Uri.parse('http://10.0.2.2/schoolManagementWeb/application/models/User_model/get_user_image/?user_id=$userId'),
      );if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        setState(() {
          imageUrl = data['image_url'];
        });
      } else {
        print('Failed to fetch user image: ${response.statusCode}');
      }
    } catch (e) {
      print('Error fetching user image: $e');
    }
  }




  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        width: 350,
        height: double.infinity,
        color: const Color(0xFF17203A),
        child: SafeArea(
          child: Column(
            children: [
              InfoCard(
                name: widget.userData['name'],
                role: widget.userData['role'],
                imageUrl: imageUrl,
              ),
              ListTile(
                leading: SizedBox(
                  height: 34,
                  width: 34,
                  child: RiveAnimation.asset("assets/RiveAssets/icons.riv")
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class InfoCard extends StatefulWidget {
  final String name, role;
  final String? imageUrl;

  const InfoCard({Key? key, required this.name, required this.role, this.imageUrl})
      : super(key: key);

  @override
  State<InfoCard> createState() => _InfoCardState();
}

class _InfoCardState extends State<InfoCard> {
  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: CircleAvatar(
        backgroundColor: Colors.white24,
        backgroundImage: widget.imageUrl != null ? NetworkImage(widget.imageUrl!) : null,
        child: widget.imageUrl == null
            ? Icon(
          CupertinoIcons.person,
          color: Colors.white,
        )
            : null,
      ),
      title: Text(
        widget.name,
        style: const TextStyle(color: Colors.white),
      ),
      subtitle: Text(
        widget.role,
        style: const TextStyle(color: Colors.white),
      ),
    );
  }
}
