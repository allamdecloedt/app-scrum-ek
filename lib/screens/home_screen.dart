import 'package:flutter/material.dart';
import '../widgets/side_menu.dart';

class MyHomePage extends StatefulWidget {
  final Map<String, dynamic> userData; // Add userData field

  const MyHomePage({Key? key, required this.userData}) : super(key: key);

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('My Home Page'),
      ),
      drawer: Drawer(
        child: SideMenu(userData: widget.userData), // Pass userData to SideMenu
      ),
      body: Center(
        child: Text('Welcome to My Home Page'),
      ),
    );
  }
}
